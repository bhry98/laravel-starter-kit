go to intro.md file [🔗](../intro.md)
# APIs in this package

---
### 1. Core

#### Locations

- **[GET] `/countries`**  
  _Get all countries_

- **[GET] /countries/{country_code}**  
  _Get country details_

- **[GET] /countries/{country_code}/governorates**  
  _Get all governorates in this country_

- **[GET] /countries/{country_code}/cities**  
  _Get all cities in this country_

- **[GET] /governorates**  
  _Get all governorates_

- **[GET] /governorates/{governorate_code}**  
  _Get governorate details_

- **[GET] /governorates/{country_code}/cities**  
  _Get all cities in this governorate_

- **[GET] /cities**  
  _Get all cities_

- **[GET] /cities/{city_code}**  
  _Get city details_

#### Enums

- **[GET] /{enum_route_code}**  
  _Get all enums child by parent code_

- **[GET] /usersGenders**  
  _Get all genders for user_

- **[GET] /usersTypes**  
  _Get all types for user_

#### System

- **[GET] /currentLocal** ❌  
  _Get current system language based on user auth (if no user auth, based on default system language)_

- **[GET] /locals** ❌  
  _Get all system locals based on locals table in admin panel_

### Auth

- **[POST] /login**  
  _Login via [login_via] value from config file_

- **[POST] /registration**  
  _Registration via [login_via] value from config file (required input)_

- **[GET|AUTH] /logout** ❌  
  _Logout authenticated user_

- **[POST] /resetPassword** ❌  
  _Reset password via [reset_password_via] value from config file to send OTP_

- **[POST] /verifyOtp** ❌  
  _Verify OTP, returns boolean (if true, user login successful and must reset password)_

### Users

- **[GET|AUTH] /me** ❌  
  _Get my profile_

- **[PUT|AUTH] /me** ❌  
  _Update my profile_

- **[POST|AUTH] /changePassword** ❌  
  _Update my password_

- **[GET|AUTH] /notifications** ❌  
  _Get my notifications_

- **[PUT|AUTH] /seenNotifications** ❌  
  _Set notifications seen by notification code_

