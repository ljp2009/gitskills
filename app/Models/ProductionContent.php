<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionContent extends Model
{
    protected $table     = 't_production_content';
    protected $guarded   = ['id'];
    public function getTextForEditAttribute(){
        $res = str_replace(["\r\n","\r","\n"],"\\n", $this->text);
        return $res;
    }
    public function isBold(){
        return $this->status == 1 || $this->status == 11;
    }
    public function isReference(){
        return $this->status == 10 || $this->status == 11;
    }
    public function isFit(){
        return $this->status == 1;
    }
}
