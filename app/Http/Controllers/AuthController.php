<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            \Log::info('Google OAuth callback started');
            \Log::info('Request parameters', request()->all());
            
            try {
                // Check if we're in development mode and using test parameters
                if (app()->environment('local') && request('code') === 'test') {
                    \Log::info('Using test mode for Google OAuth');
                    
                    // Create a test user for development
                    $testUser = User::firstOrCreate(
                        ['email' => 'test@example.com'],
                        [
                            'name' => 'Test User',
                            'google_id' => 'test_google_id',
                            'password' => Hash::make('password'),
                            'email_verified_at' => now(),
                        ]
                    );
                    
                    Auth::login($testUser);
                    
                    // Store token in session for API access
                    session(['auth_token' => $testUser->createToken('auth-token')->plainTextToken]);
                    
                    // Redirect to dashboard
                    return redirect()->route('dashboard')->with('success', 'Test login successful!');
                }
                
                $googleUser = Socialite::driver('google')->stateless()->user();
                \Log::info('Google user data received', ['user_id' => $googleUser->getId(), 'email' => $googleUser->getEmail()]);
            } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
                \Log::error('Invalid state exception', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid state parameter. Please try logging in again.',
                    'error_type' => 'invalid_state'
                ], 400);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $responseBody = $e->getResponse()->getBody();
                $errorData = json_decode($responseBody, true);
                
                \Log::error('Google API error', [
                    'error' => $e->getMessage(), 
                    'response' => $responseBody,
                    'error_data' => $errorData
                ]);
                
                $errorMessage = 'Google authentication failed.';
                if (isset($errorData['error_description'])) {
                    $errorMessage .= ' ' . $errorData['error_description'];
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error_type' => 'google_api_error',
                    'error_details' => $errorData
                ], 400);
            }

            // Check if user exists
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Check if user exists with same email
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Update existing user with Google ID
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'password' => Hash::make(Str::random(16)), // Random password for Google users
                        'email_verified_at' => now(), // Google emails are verified
                    ]);
                }
            }

            // Login user
            Auth::login($user);

            // Store token in session for API access
            session(['auth_token' => $user->createToken('auth-token')->plainTextToken]);

            // Redirect to dashboard
            return redirect()->route('dashboard')->with('success', 'Login successful!');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Redirect to Facebook OAuth
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')
            ->scopes(['public_profile'])
            ->redirect();
    }

    /**
     * Handle Facebook OAuth callback
     */
    public function handleFacebookCallback()
    {
        try {
            \Log::info('Facebook OAuth callback started');
            \Log::info('Request parameters', request()->all());
            
            $facebookUser = Socialite::driver('facebook')->user();
            \Log::info('Facebook user data received', ['user_id' => $facebookUser->getId(), 'name' => $facebookUser->getName()]);

            // Check if user exists
            $user = User::where('facebook_id', $facebookUser->getId())->first();

            if (!$user) {
                // For Facebook, we might not get email, so we'll use a generated email
                $email = $facebookUser->getEmail() ?? $facebookUser->getId() . '@facebook.local';
                
                // Check if user exists with same email
                $user = User::where('email', $email)->first();

                if ($user) {
                    // Update existing user with Facebook ID
                    $user->update([
                        'facebook_id' => $facebookUser->getId(),
                        'avatar' => $facebookUser->getAvatar(),
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $facebookUser->getName(),
                        'email' => $email,
                        'facebook_id' => $facebookUser->getId(),
                        'avatar' => $facebookUser->getAvatar(),
                        'password' => Hash::make(Str::random(16)), // Random password for Facebook users
                        'email_verified_at' => $facebookUser->getEmail() ? now() : null, // Only verify if we got email
                    ]);
                }
            }

            // Login user
            Auth::login($user);

            // Store token in session for API access
            session(['auth_token' => $user->createToken('auth-token')->plainTextToken]);

            // Redirect to dashboard
            return redirect()->route('dashboard')->with('success', 'Facebook login successful!');

        } catch (\Exception $e) {
            \Log::error('Facebook OAuth error', ['error' => $e->getMessage()]);
            return redirect()->route('login')->with('error', 'Facebook login failed: ' . $e->getMessage());
        }
    }

    /**
     * Redirect to Twitter OAuth
     */
    public function redirectToTwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }

    /**
     * Handle Twitter OAuth callback
     */
    public function handleTwitterCallback()
    {
        try {
            $twitterUser = Socialite::driver('twitter')->user();

            // Check if user exists
            $user = User::where('twitter_id', $twitterUser->getId())->first();

            if (!$user) {
                // Check if user exists with same email
                $user = User::where('email', $twitterUser->getEmail())->first();

                if ($user) {
                    // Update existing user with Twitter ID
                    $user->update([
                        'twitter_id' => $twitterUser->getId(),
                        'avatar' => $twitterUser->getAvatar(),
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $twitterUser->getName(),
                        'email' => $twitterUser->getEmail(),
                        'twitter_id' => $twitterUser->getId(),
                        'avatar' => $twitterUser->getAvatar(),
                        'password' => Hash::make(Str::random(16)), // Random password for Twitter users
                        'email_verified_at' => now(), // Twitter emails are verified
                    ]);
                }
            }

            // Login user
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $user->createToken('auth-token')->plainTextToken ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register user with email and password
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $user->createToken('auth-token')->plainTextToken ?? null,
        ]);
    }

    /**
     * Login user with email and password
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $user->createToken('auth-token')->plainTextToken ?? null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Revoke token if using Sanctum
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    }
}
