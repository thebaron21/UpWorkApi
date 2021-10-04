<?php
namespace App\Help;
use Illuminate\Http\Request;
use League\Flysystem\Exception;

class UploadImage{
    public $fileName;
    public function __construct( Request $request , $type )
    {
            $file = $request->file('image');
            $baseName = $type . "_" .date("h_m_s_Y_M_D") . "_" .$file->getClientOriginalName();
            $file->storeAs(
                'public',
                $baseName
            );
            $this->fileName = $baseName;

    }
}
