<?php

namespace App\Helpers\Constants;

use App\Enums\Gender;
use Illuminate\Validation\Rule;

class ValidationConstant{

    const EMAIL = 'required|string|email|exists:users,email';
    const EMAIL_UNIQUE = 'required|string|email|unique:users,email';
    const USERNAME = 'required|string|email|exists:users,username';
    const USERNAME_UNIQUE = 'required|string|unique:users,username';
    const PASSWORD_NO_CONFIRM = 'required|string|min:8';
    const PASSWORD = 'required|string|min:8|confirmed';

    const VAR_CHAR = 'required|string|max:250';
    const ARRAY = 'sometimes|array';

    public static function EXISTS_IN($table, $column = 'id'){
        return "sometimes|int|exists:{$table},{$column}";
    }
   
    
}