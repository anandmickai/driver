<?php


use App\Http\Controllers\Bank\AddBankDetailsController;
use App\Http\Controllers\Bank\DeleteBankAccountController;
use App\Http\Controllers\Bank\GetBankDetailsController;
use App\Http\Controllers\DriverProfile\DriverProfileController;
use App\Http\Controllers\General\LogOutController;
use App\Http\Controllers\General\ProfileController;
use App\Http\Controllers\General\UploadProfilePicController;
use App\Http\Controllers\Kyc\AddressProofController;
use App\Http\Controllers\Kyc\AddVehicleInsuranceController;
use App\Http\Controllers\Kyc\AddVehicleRegistrationController;
use App\Http\Controllers\Kyc\DrivingLicenceController;
use App\Http\Controllers\Kyc\IdentityProofController;
use App\Http\Controllers\Kyc\VehicleController;
use App\Http\Controllers\Kyc\WorkPermitProofController;
use App\Http\Controllers\Login\StepOneController;
use App\Http\Controllers\PickUp\BookingOtpValidateController;
use App\Http\Controllers\PickUp\UploadPictureController;
use App\Http\Controllers\Registration\StepOneController as RegistrationStepOneController;
use App\Http\Controllers\Login\StepTwoController;
use App\Http\Controllers\Registration\StepTwoController as RegistrationStepTwoController;
use App\Http\Controllers\MasterData\CountriesController;
use App\Http\Controllers\MasterData\DocumentsController;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\Vehicle\GetVehicleDetailsController;
use App\Http\Controllers\Wallet\BalanceController;
use App\Http\Controllers\Wallet\MyWalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'registration', 'namespace' => 'Registration'], function ($router) {
    Route::post('stepOne', [RegistrationStepOneController::class, 'stepOne'])->middleware('device.check')->name('registration.stepOne');
    Route::post('stepTwo', [RegistrationStepTwoController::class, 'stepTwo'])->middleware( 'device.check')->name('registration.stepTwo');
    Route::post('resendOtp', [RegistrationStepOneController::class,'resendRegistrationOtp'])->name('registration.resendOtp');
});

Route::group(['prefix' => 'login', 'namespace' => 'Login'], function ($router) {
    Route::post('stepOne', [StepOneController::class,'index'])->middleware( 'device.check')->name('login.stepOne');
    Route::post('stepTwo', [StepTwoController::class,'index'])->middleware( 'device.check')->name('login.stepTwo');
    Route::post('resendOtp', [StepOneController::class,'resendLoginOtp'])->name('login.resendOtp');
});


Route::group(['middleware' => ['token.auth'], 'prefix' => 'me', 'namespace' => 'General'], function ($router) {
    Route::post('logout', [LogOutController::class, 'index']);
    Route::post('/', [ProfileController::class, 'index']);
    // Route::post('upload', 'UploadDocumentsController@uploadDocument');
    Route::post('uploadProfilePic', [UploadProfilePicController::class, 'uploadProfilePic']);
    Route::get('document', 'DriverDocumentsController@index');
});

Route::group(['middleware' => ['token.auth'], 'prefix' => 'me/wallet', 'namespace' => 'Wallet'], function ($router) {
    Route::post('/', [MyWalletController::class,'index']);
    Route::post('balance', [BalanceController::class,'index']);
});

Route::namespace('MasterData')->group(function () {
    Route::get('countries', [CountriesController::class, 'index']);
    Route::get('documents', [DocumentsController::class, 'index']);
});

Route::group(['middleware' => ['token.auth'], 'namespace' => 'CustomerProfile'], function ($router) {
    Route::post('UpdateCustomerProfile', [ProfileController::class,'index']);
});


Route::group(['middleware' => ['token.auth'], 'prefix' => '', 'namespace' => 'DriverProfile'], function ($router) {
    Route::post('updateDriverProfile', [DriverProfileController::class, 'index']);
});
Route::group(['middleware' => ['token.auth'], 'prefix' => '', 'namespace' => 'PickUp'], function ($router) {
    Route::post('uploadPickUpImage', [UploadPictureController::class,'uploadImage']);
    Route::post('bookingOtpValidate', [BookingOtpValidateController::class,'index']);
});
Route::group(['middleware' => ['token.auth'], 'prefix' => '', 'namespace' => 'Vehicle'], function ($router) {
    Route::get('getVehicleDetails', [GetVehicleDetailsController::class,'index']);
});
Route::group(['middleware' => ['token.auth'], 'prefix' => '', 'namespace' => 'Bank'], function ($router) {
    Route::get('getBankDetails', [GetBankDetailsController::class,'index']);
    Route::post('addBankDetails', [AddBankDetailsController::class,'index']);
    Route::post('deleteBankDetails', [DeleteBankAccountController::class, 'delete']);
});
Route::group(['middleware' => ['token.auth'], 'prefix' => 'kyc', 'namespace' => 'Kyc'], function ($router) {
    Route::post('addressProof/upload', [AddressProofController::class,'storeAddress']);
    Route::post('identityProof/upload', [IdentityProofController::class,'storeIdentity']);
    Route::post('workPermit/upload', [WorkPermitProofController::class,'storePermit']);
    Route::post('addVehicleDetails/upload', [VehicleController::class,'addVehicleDetails']);
    Route::post('addVehicleRegistration/upload', [AddVehicleRegistrationController::class,'addVehicleRegistration']);
    Route::post('addVehicleInsurance/upload', [AddVehicleInsuranceController::class,'addVehicleInsurance']);
    Route::post('addVehicleLicence/upload', [DrivingLicenceController::class, 'storeLicence']);
});

Route::post('testPush', [TestController::class,'sendPush']);
Route::post('testEmail', [TestController::class,'sendEmail']);
