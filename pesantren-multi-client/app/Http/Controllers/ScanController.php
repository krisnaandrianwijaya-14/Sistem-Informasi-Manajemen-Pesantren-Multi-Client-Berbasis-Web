<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentCard;
use App\Models\Wallet;
use App\Services\WalletService;
use DomainException;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ScanController extends Controller
{
    private const KASIR_ROLES = ['kasir', 'admin_pesantren'];
    private const ADMIN_ROLES = ['admin_pesantren'];

    private function tenantId(Request $r): int
    {
        return (int) DB::table('client_users')->where('user_id', $r->user()->id)->value('client_id');
    }

    private function allow(Request $r, array $roles): void
    {
        abort_unless(in_array($r->user()->role, $roles, true), 403);
    }

    public function kasirPage(Request $r)
    {
        $this->allow($r, self::KASIR_ROLES);

        return view('scan.kasir');
    }

    public function lookup(Request $r): JsonResponse
    {
        $this->allow($r, self::KASIR_ROLES);
        $data = $r->validate(['code' => 'required|string|max:100']);

        $card = StudentCard::where('token', $data['code'])
            ->where('status', 'active')
            ->where('tenant_id', $this->tenantId($r))
            ->first();

        $student = $card ? Student::withoutGlobalScopes()->find($card->student_id) : null;
        if (! $student) {
            return response()->json(['ok' => false, 'message' => 'Kartu tidak valid atau diblokir.'], 404);
        }

        $wallet = Wallet::firstOrCreate(
            ['tenant_id' => $student->tenant_id, 'student_id' => $student->id],
            ['balance' => 0]
        );

        return response()->json([
            'ok' => true,
            'wallet_id' => $wallet->id,
            'name' => $student->full_name,
            'balance' => $wallet->balance,
        ]);
    }

    public function bayar(Request $r, WalletService $wallets): JsonResponse
    {
        $this->allow($r, self::KASIR_ROLES);
        $data = $r->validate([
            'wallet_id' => 'required|integer',
            'amount' => 'required|integer|min:1',
            'key' => 'required|string|max:100',
        ]);

        $wallet = Wallet::findOrFail($data['wallet_id']);
        abort_unless($wallet->tenant_id === $this->tenantId($r), 403);

        try {
            $trx = $wallets->debit($wallet->id, $data['amount'], $data['key'], $r->user()->id, 'kantin');
        } catch (DomainException|InvalidArgumentException $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true, 'balance' => $trx->balance_after]);
    }

    public function kartu(Request $r, int $student)
    {
        $this->allow($r, self::ADMIN_ROLES);

        $s = Student::withoutGlobalScopes()->findOrFail($student);
        abort_unless($s->tenant_id === $this->tenantId($r), 403);

        $card = StudentCard::where('student_id', $s->id)->where('status', 'active')->first()
            ?? StudentCard::issue($s);

        $qr = (new Builder(writer: new SvgWriter(), data: $card->token, size: 280, margin: 8))
            ->build()->getDataUri();

        return view('scan.kartu', ['student' => $s, 'qr' => $qr]);
    }
}