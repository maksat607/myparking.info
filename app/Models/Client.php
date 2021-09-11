<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['firstname', 'lastname', 'middlename', 'passport',
        'address', 'phone', 'email', 'legal_type_id', 'preferred_contact_method',
        'organization_name', 'organization_address', 'organization_phone', 'inn', 'orgn', 'issuance_document'];

    protected static $issuanceDocumentOptions = ['Заявка от партнера','Право собственности','Оба варианта'];
    protected static $issuanceIndividualLegalOptions = [1 => 'Физ. лицо', 2 => 'Юр. лицо'];
    protected static $issuancePreferredContactMethodOptions = ['phone', 'email'];

    public function attachments()
    {
        return $this->morphMany(Attachment::class,'attachable');
    }

    public static function issuanceDocumentOptions()
    {
        return self::$issuanceDocumentOptions;
    }
    public static function issuanceIndividualLegalOptions()
    {
        return self::$issuanceIndividualLegalOptions;
    }
    public static function issuancePreferredContactMethodOptions()
    {
        return self::$issuancePreferredContactMethodOptions;
    }
}