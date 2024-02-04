<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'maxRegistrationTries'          => "Seems you're facing difficulty to register. Please contact support: " . config(
            'needs.supportEmail'
        ),
    'otpSent'                       => 'OTP sent to the registered mobile number',
    'accountFriezed'                => 'Your account is frozen. Please try after ' . config(
            'needs.freezeTime'
        ) . ' minutes',
    'invalidOtp'                    => 'Please enter valid OTP shared on register mobile number.',
    'regSuccess'                    => 'Thanks for registering with us.',
    'otpExpired'                    => 'OTP has been expired',
    'maxOtpAttempts'                => 'Maximum wrong otp attempts consumed',
    'maxOtpResend'                  => 'Maximum OTP Resend consumed',
    'userNotAllowed'                => 'OOps..! You are not allowed to do this activity',
    'userNotRegistered'             => 'Mobile number not registered with us.',
    'loggedOut'                     => 'Successfully logged out',
    'registrationNotFinished'       => 'Your mobile number is not registered with us',
    'userAccountDeleted'            => 'User not allowed to log in.',
    'loginSuccess'                  => 'Welcome! How are you doing today.',
    'deviceInfoMissing'             => 'Device Info is required.',
    'authError'                     => 'Auth error occurred.',
    'docUploadSuccess'              => 'Document uploaded successfully',
    'profilePicUploadSuccess'       => 'Profile picture uploaded successfully',
    'docUrlInvalid'                 => 'Document URL is invalid',
    'noRecordsFound'                => 'No Records found',
    'userAlreadyRegistered'         => 'User already registered with us. Please log in',
    'cannotProcess'                 => 'Unprocessable Entity',
    'invalidVehicleRegExpiryDate'   => 'registrationExpiringOn should be greater than Registration date',
    'invalidVehicleType'            => 'Vehicle type is invalid',
    'invalidBodyType'               => 'Body Type of vehicle type is invalid',
    'addVehicleSuccess'             => 'Vehicle information added successfully',
    'addVehicleInsuranceSuccess'    => 'Vehicle insurance added successfully',
    'addVehicleRegistrationSuccess' => 'Vehicle registration added successfully',
    'vehicleAlreadyAdded'           => 'Vehicle details already available',
    'somethingWentWrong'            => 'Some thing went wrong!',
    'cannotRegisteredAsDriver'      => 'Cannot us same mobile number for registering customer and driver',
];
