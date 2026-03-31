<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ResponseTrait;
    // ==========================================
    // POST /api/V1/register
    // ==========================================
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);
        // Create Token
        $token = $user->createToken('auth_token')->plainTextToken;
        $data = [
            'user' => $user,
            'token' => $token,
        ];
        return $this->successResponse($data, 'تم إنشاء الحساب بنجاح', 201);
    }

    // ==========================================
    // POST /api/V1/login
    // ==========================================
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['البيانات المدخلة غير صحيحة'],
            ]);
        }

        // حذف التوكنات القديمة لنفس الجهاز (منع التكرار)
        $user->tokens()->where('name', $request->device_name)->delete();

        $token = $user->createToken($request->device_name)->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token,
        ];
        return $this->successResponse($data, 'تم تسجيل الدخول بنجاح');
    }

    // ==========================================
    // POST /api/logout         [Bearer Token]
    // ==========================================
    public function logout(Request $request)
    {
        // إبطال التوكن ومسحه من السيرفر (حماية الخصوصية)
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }

    // ==========================================
    // POST /api/password/forgot
    // ==========================================
    public function forgotPassword(Request $request)
    {
        $data = $request->validated();

        // إرسال رابط إعادة التعيين عبر نظام الـ Mail في لارافيل
        $status = Password::sendResetLink(
            $data
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'تم إرسال رابط إعادة تعيين الباسورد على الإيميل',
            ]);
        }

        return response()->json([
            'message' => 'حدث خطأ أثناء الإرسال، حاول مرة أخرى',
        ], 500);
    }
}
