<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Image as ModelsImage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

if(!function_exists('getUser'))
{
    function getUser($email)
    {
        $user = User::where('email', $email)->first();
        return $user;
    }
}
if (!function_exists('getAuthCompanyPrice')) {
    function getAuthCompanyPrice($productId)
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();
        $company = Company::where('typo', $user->user_type)->first();
        if ($company) {
            $product = Product::find($productId);
            $prices = $product->company_prices;
            if ($prices) {
                $prices = json_decode($prices, true);
                if (isset($prices[$company->id])) {
                    return $prices[$company->id]['price'];
                }
            }
        }
        return null;
    }
}



if(!function_exists('slug'))
{
    function nameSlug($string, $spaceRepl = "-")
    {
        $string = str_replace("&", "and", $string);
        $string = preg_replace("/[^a-zA-Z0-9 _-]/", "", $string);
        $string = preg_replace("/[ ]+/", " ", $string);
        $string = str_replace(" ", $spaceRepl, $string);
        return $string;
    }
}

if(!function_exists('limited_str')) {
    function limited_str($ur_str,$count='40')
    {
        return  (strlen($ur_str) > $count) ? substr($ur_str,0,$count).'...' :$ur_str;
    }
}
if (!function_exists('isImage')) {
    function isImage($path,$file_name)
    {
        $paths='public/uploads/'.$path.'/'.$file_name;
        if(file_exists($paths)&&!empty($file_name))
        {
           return asset($paths);
        }else{
            return asset('public/img/blank_image.png');
        }
    }
}
if (!function_exists('deleteImageIfExists')) {
    function deleteImageIfExists($path, $imageName)
    {
        if (!empty($imageName)) {
            $imagePath = 'public/uploads/'.$path.'/'.$imageName;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }
}

function getImages($productId, $type)
{
    $images = Image::where('parent_id', $productId)->where('type', $type)->get();
    return $images->isEmpty() ? '' : $images;
}

function getcolumnname($table, $columm, $id) {
    $query = DB::table($table);
    $query->where('id', $id);
    $rows = $query->first();
    if (!empty($rows)) {
        return $rows->$columm;
    } else {
        return '';
    }
}
function createThumbnail($path, $width, $height) {
    $img = Image::make($path)->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
    });
    $img->save($path);
}
function getproductprice($id) {
    $query = DB::table('product_variants');
    $query->where('product_id', $id);
    $rows = $query->first();
    $today = Carbon::now();
    $getmonths = DB::table('product_variants')->whereRaw('"' . $today . '" between `from_date` and `to_date`')->where('product_id', $id)->first();
    if (!empty($rows->special_price) && !empty($getmonths)) {
        return number_format($rows->special_price, 2);
    } elseif (!empty($rows->price)) {
        return number_format($rows->price, 2);
    } else {
        return '';
    }
}
function getproductcutprice($id) {
    $query = DB::table('product_variants');
    $query->where('product_id', $id);
    $rows = $query->first();
    $today = Carbon::now();
    $getmonths = DB::table('product_variants')->whereRaw('"' . $today . '" between `from_date` and `to_date`')->where('product_id', $id)->first();
    if (!empty($getmonths)) {
        return number_format($rows->price, 2);
    } else {
        return '';
    }
}
function getproductvariant($id) {
    $query = DB::table('product_variants');
    $query->where('product_id', $id);
    $query->where('is_active', 1);
    $query->orderBy('id', 'asc');
    $rows = $query->get();
    return $rows;
}
function getproductunit($id) {
    $query = DB::table('product_variants');
    $query->where('product_id', $id);
    $rows = $query->first();
    if (!empty($rows->weight)) {
        return $rows->weight . ' ' . $rows->weight_type;
    } else {
        return '';
    }
}
function randomNumber($length) {
    $result = '';
    for ($i = 0;$i < $length;$i++) {
        $result.= mt_rand(0, 9);
    }
    return $result;
}
/*
 **product helper start
*/
function generateProductEANCode($eanCodeDigits) {
    $flag = true;
    $eancode = '';
    while ($flag) {
        $eancode = randomNumber($eanCodeDigits);
        $is_exist = DB::table('product_variants')->select('ean_Code')->where(['ean_Code' => $eancode])->exists();
        if (!$is_exist) {
            $flag = false;
        }
    }
    return $eancode;
}
function validateProductEANCode($ean_code, $eanCodeDigits) {
    $is_exist = DB::table('product_variants')->select('ean_Code')->where(['ean_Code' => $ean_code])->exists();
    $new_ean_code = '';
    if ($is_exist) {
        $new_ean_code = generateProductEANCode($eanCodeDigits);
    } else {
        $new_ean_code = $ean_code;
    }
    return $new_ean_code;
}
function generateProductSKUCode($skuCodeDigits) {
    $flag = true;
    $skucode = '';
    while ($flag) {
        $skucode = "SKU" . randomNumber($skuCodeDigits);
        $is_exist = DB::table('product_variants')->select('sku_code')->where(['sku_code' => $skucode])->exists();
        if (!$is_exist) {
            $flag = false;
        }
    }
    return $skucode;
}
function validateProductSKUCode($sku_code, $skuCodeDigits) {
    $is_exist = DB::table('product_variants')->select('sku_code')->where(['sku_code' => $sku_code])->exists();
    $new_sku_code = '';
    if ($is_exist) {
        $new_sku_code = generateProductSKUCode($skuCodeDigits);
    } else {
        $new_sku_code = $sku_code;
    }
    return $new_sku_code;
}
function createProductSlug($title, $id = 0) {
    // Normalize the title
    $slug = Str::slug($title);
    // Get any that could possibly be related.
    // This cuts the queries down by doing it once.
    // $allSlugs = $this->getRelatedSlugs($slug, $id);
    $allSlugs = DB::table('products')->select('slug')->where('slug', 'like', $slug . '%')->where('id', '<>', $id)->get();
    // If we haven't used it before then we are all good.
    if (!$allSlugs->contains('slug', $slug)) {
        return $slug;
    }
    // Just append numbers like a savage until we find not used.
    for ($i = 1;$i <= 100;$i++) {
        $newSlug = $slug . '-' . $i;
        if (!$allSlugs->contains('slug', $newSlug)) {
            return $newSlug;
        }
    }
    throw new \Exception('Can not create a unique slug');
}
function createColumnString($option) //for product variant
{
    $str = $option->option . '_' . $option->id;
    $str = trim($str);
    $str = str_replace('#', '', $str);
    $str = str_replace(';', '', $str);
    $str = str_replace('!', '', $str);
    $str = str_replace('"', '', $str);
    $str = str_replace('$', '', $str);
    $str = str_replace('%', '', $str);
    $str = str_replace('(', '', $str);
    $str = str_replace(')', '', $str);
    $str = str_replace('*', '', $str);
    $str = str_replace('+', '', $str);
    $str = str_replace('/', '', $str);
    $str = str_replace('\'', '', $str);
    $str = str_replace('<', '', $str);
    $str = str_replace('>', '', $str);
    $str = str_replace('=', '', $str);
    $str = str_replace('?', '', $str);
    $str = str_replace('[', '', $str);
    $str = str_replace(']', '', $str);
    $str = str_replace('\\', '', $str);
    $str = str_replace('^', '', $str);
    $str = str_replace('`', '', $str);
    $str = str_replace('{', '', $str);
    $str = str_replace('}', '', $str);
    $str = str_replace('|', '', $str);
    $str = str_replace('~', '', $str);
    $str = str_replace('&', '', $str);
    $str = str_replace(',', '', $str);
    $str = strtolower($str);
    $str = str_replace(" ", '_', $str);
    $str = str_replace("'", '', $str);
    return $str;
}
function isTableColumnExists($tbl_name, $col_name) {
    $col_data = DB::select("SHOW COLUMNS FROM `" . $tbl_name . "` LIKE '" . $col_name . "'");
    return $col_data ? TRUE : FALSE;
}
function getOptionColumnNameById($option_id) {
    $col_data = DB::select("SELECT `id`, `option` FROM `options` WHERE id = " . $option_id);
    $col_name = createColumnString($col_data[0]);
    if (!isTableColumnExists('product_variants', $col_name)) {
        DB::statement('ALTER TABLE product_variants ADD ' . $col_name . ' VARCHAR( 255 ) NULL after stock');
    }
    return $col_name;
}
/*
 **product helper end
*/
function getCurrency() {
    $query = DB::table('currencies');
    $query->where('id', 1);
    $rows = $query->first();
    if (!empty($rows->currency)) {
        return $rows->currency;
    } else {
        return '';
    }
}

function getcity($id) {
    $query = DB::table('cities');
    $query->where('id', $id);
    $rows = $query->first();
    if (!empty($rows->name)) {
        return $rows->name;
    } else {
        return '';
    }
}

function getstate($id) {
    $query = DB::table('states');
    $query->where('id', $id);
    $rows = $query->first();
    if (!empty($rows->name)) {
        return $rows->name;
    } else {
        return '';
    }
}

function getcountry($id) {
    $query = DB::table('countries');
    $query->where('id', $id);
    $rows = $query->first();
    if (!empty($rows->name)) {
        return $rows->name;
    } else {
        return '';
    }
}


/*
* @param1 : Plain String
* @param2 : Working key provided by CCAvenue
* @return : Decrypted String
*/
function encrypts($plainText,$key)
{
	$key = hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
	$encryptedText = bin2hex($openMode);
	return $encryptedText;
}

/*
* @param1 : Encrypted String
* @param2 : Working key provided by CCAvenue
* @return : Plain String
*/
function decrypts($encryptedText,$key)
{
	$key = hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$encryptedText = hextobin($encryptedText);
	$decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
	return $decryptedText;
}

function hextobin($hexString)
 {
	$length = strlen($hexString);
	$binString="";
	$count=0;
	while($count<$length)
	{
	    $subString =substr($hexString,$count,2);
	    $packedString = pack("H*",$subString);
	    if ($count==0)
	    {
			$binString=$packedString;
	    }

	    else
	    {
			$binString.=$packedString;
	    }

	    $count+=2;
	}
        return $binString;
  }
  function camelCase($string) {
    $string = str_replace(' ', '',
        ucwords(str_replace(['-', '_'],
        ' ', $string))
    );
    return $string;
 }
?>
