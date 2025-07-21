<?php

namespace App\OpenApi;

/**
 * @OA\Info(
 *     title="Music Rental System API",
 *     version="1.0.0",
 *     description="API documentation for the Music Instrument Rental System. This API allows customers to browse instruments, create rental orders, and track their bookings.",
 *     @OA\Contact(
 *         name="Music Rental Support",
 *         email="support@musicrental.com"
 *     ),
 *     @OA\License(
 *         name="MIT License",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost/api",
 *     description="Local Development Server"
 * )
 */
class Info {}
