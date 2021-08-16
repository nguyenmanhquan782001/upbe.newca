<?php
namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Datatables;
use Illuminate\Support\helpers;
class AffiliateController extends Controller
{
    public $member;
    protected $affiliateRepo;
    protected $affiliateTransactionRepo;

    public function affiliate(Request $request)
    {
        try {
            $this->member = Auth::user();
            $cURLConnection = curl_init();
            $affiliate_users_link = config('services.affiliate.endpoint')."account/affiliate/7/orders";
            curl_setopt($cURLConnection, CURLOPT_URL, $affiliate_users_link);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
            $wards = curl_exec($cURLConnection);
            curl_close($cURLConnection);
            $orders =  json_decode($wards);
            return view('home',compact('orders'));

        } catch (Exception $e) {
            ThrowExceptionError($e);
            return [];
        }

    }
    public function update(Request $request)
    {
        $info = Auth::user();
        return view('update',compact('info'));
    }
    public function updateBankInfo(Request $request)
    {
        $bank_info = array_only($request->all(),['bank_name','bank_branch','bank_account','bank_no']);
        $email = $request->get('email');
        $target_user =  User::where('email',$email)->firstOrFail();
        $target_user->affiliate()->update(['affiliate_banks'=>json_encode([$bank_info])]);
        return response()->json($bank_info);
    }
    public function getBankInfo(Request $request)
    {
        $email = $request->get('email');
        $target_user =  User::where('email',$email)->firstOrFail();
        $bank_info = $target_user->affiliate()->get('affiliate_banks');
        return response()->json($bank_info);
    }
    public function getUserInfo(Request $request)
    {
        $email = $request->get('email');
        $user_info =  User::where('email',$email)->firstOrFail();
        return response()->json($user_info);
    }
    public function updateUserInfo(Request $request)
    {
        $email = $request->get('email');
        $user_info = array_only($request->all(),['name','gender','birthday','phone','province','district','ward','address']);
        $target_user =  User::where('email',$email)->firstOrFail();
        $target_user->fill($user_info);
        $target_user->save();
        return response()->json($user_info);
    }
    public function updateAffiliateInfo(Request $request)
    {
        $email = $request->get('email');
        $affiliate_code = $request->get('affiliate_code');
        $target_user =  User::where('email',$email)->firstOrFail();
        $target_user->affiliate()->update(['affiliate_code'=>$affiliate_code]);
        return response()->json(['success' => true]);
    }
    public function affiliateTools(Request $request)
    {
        Theme::set('breadcrumbs', Theme::partial('sections.breadcrumbs', ['data' => getBreadcrumbs('', trans('main.affiliate'))]));
        $has_access_token=!empty($this->member->affiliate->bitly_access_token)?true:false;
        return Theme::view('user.affiliate_tools',compact('has_access_token'));
    }

    public function affiliateSettings(Request $request)
    {
        Theme::set('breadcrumbs', Theme::partial('sections.breadcrumbs', ['data' => getBreadcrumbs('', trans('main.affiliate_settings'))]));
        $this->setSeo(['meta_title' => config('wbsetting.website_name') . ' - ' . trans('main.affiliate_settings')]);
        $max_profit = 0;
        $get_settings = \Setting::get('affiliate');
        if(!empty($get_settings)){
            $max_profit = (int)$get_settings['profit'];
        }
        $settings=@json_decode($this->member->affiliate()->first()->settings, true);
        //$coupons = Coupon::where('user_id', Auth::id())->get();
        $is_active_affiliate=!empty($this->member->affiliate)?true:false;
        return Theme::view('user.affiliate_settings',compact('settings', 'max_profit', 'coupons', 'is_active_affiliate'));
    }
    public function affiliateSettingsStore(Request $request)
    {
        $dataRequest = $request->all();
        $error = [];
        if(!empty($dataRequest['coupon'])){
            $user_id = Auth::id();
            foreach ($dataRequest['coupon'] as $key => $value) {
                if( (int)$value['money'] <= (int)$dataRequest['max'])
                {
                    $data = \ECommerce::createUpdateCouponStore($value, $user_id);
                    if($data == false){
                        \Session::flash('errorMessage', trans('message.message_no_coupon'));
                        return back();
                    }
                }
                else{
                    \Session::flash('errorMessage', trans('message.message_no_discount_value'));
                    return back();
                }
            }
        }
        $data = array_only($request->all(),['settings']);
        $this->member->affiliate()->update(['settings'=>json_encode($data['settings'])]);
        \Session::flash('doneMessage', trans('message.update_success'));
        return back();
    }

    public function createShortLink(Request $request)
    {
        $request->link = $request->link.'?ref='.$this->member->id;
        if(strpos($request->link,$request->getSchemeAndHttpHost())!==0){
            return response()->json(['success' => false,'msg'=>'Đường dẫn không hợp lệ']);
        }
        if($this->member->affiliate->shortLinks()->where('original_link',$request->link)->count()){
            return response()->json(['success' => false,'msg'=>'Đường dẫn đã tồn tại']);
        }
        if($this->member->affiliate->bitly_access_token){
            config(['bitly4laravel.access_token' => $this->member->affiliate->bitly_access_token]);
        }
        else{
            config(['bitly4laravel.access_token' => config('wbsetting.affiliate.generic_access_token')]);
        }
        $response = \Bitly::shorten($request->link);
        if($response->status_code==200){
            $this->member->affiliate->shortLinks()->create(['original_link'=>$request->link,'short_link'=>$response->data->url]);
            return response()->json(['success' => true]);
        }
        else{
            if($response->status_txt=='INVALID_ARG_ACCESS_TOKEN'){
                $response->status_txt='Token sử dụng không hợp lệ. Vui lòng cập nhật token khác và thử lại.';
            }
            return response()->json(['success' => false,'msg'=>$response->status_txt]);
        }

    }

    public function shortLinks()
    {
        return app()->make(AffiliateShortLinksRepository::class)->shortLinksDatatable(@$this->member->affiliate->id);
    }

    public function updateAccessToken(Request $request)
    {
        $this->member->affiliate()->update(['bitly_access_token'=>$request->access_token]);
        return response()->json(['success' => true]);
    }
    public function deleteCoupon(Request $request){
        $dataRequest = $request->all();
        if(!empty($dataRequest['coupon'])){
            return Coupon::where('user_id', Auth::id())->where('code', $dataRequest['coupon'])->delete();
        }
    }

    public function memberRegistryAffiliate()
    {
        $this->member->update(['type' => 2]);
        $this->initConfig();
        $this->sendMailTemplate($this->member->email,'affiliate_confirm_register',['fullname'=>$this->member->fullname,'email'=>$this->member->email,'affiliate_link'=>url('?rel='.$this->member->id),'affiliate_id'=>$this->member->id],true);

        //send mail admin
        $this->sendMailTemplate(getReceiveEmail('affiliate.receive_email'),'affiliate_confirm_register_to_admin',['fullname'=>$this->member->fullname,'email'=>$this->member->email,'affiliate_link'=>url('?rel='.$this->member->id),'affiliate_id'=>$this->member->id],true);
        return redirect()->back();
    }
    public function gotoProductLink($link){
        $affiliate_link_model = Affiliate::where('affiliate_code',trim($link))->firstOrFail();
        //dd($affiliate_link_model);
        if($affiliate_link_model){
            $affiliate_user_id = $affiliate_link_model->user_id;
            return redirect('http://test.newca.vn?ref='.$affiliate_user_id);
        }
    }
    public function listToken(Request $request)
    {
        $email = $request->get('email');
        $target_user =  User::where('email',$email)->firstOrFail();
        $token_list =  Token::where('user_id', $target_user->id)->get();
        return response()->json($token_list);
    }
    public function addToken(Request $request)
    {
        $email = $request->get('email');
        $token_info = array_only($request->all(),['cn','serial']);
        $target_user =  User::where('email',$email)->firstOrFail();
        $token_info['user_id'] = $target_user->id;
        $check_existed = array('user_id'=>$target_user->id,'serial'=>$token_info['serial']);
        $token_obj = Token::createOrUpdate($token_info,$check_existed);
        return response()->json($token_obj);
    }
    public function socialLogin(Request $request)
    {
        $email = $request->get('email');
        $name = $request->get('Name');
        $token = $request->get('token');
        $profile_photo_path = $request->get('Image');
        $finduser = User::where('email', $email)->first();

        if(!$finduser){
            $finduser = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $token,
                'profile_photo_path'=> $profile_photo_path,
                'password' => encrypt('admin@123')
            ]);
        }
        return response()->json($finduser);
    }
    public function loginToken(Request $request)
    {
        $cn = $request->get('cn');
        $serial = $request->get('serial');
        $findToken = Token::where('cn', $cn)->where('serial', $serial)->first();
        if($findToken){
            $finduser = $finduser = User::where('id', $findToken->user_id)->first();
        }
        else{
            $finduser = User::create([
                'name' => $cn,
                'email' => $this ->generateEmailAddress(),
                'password' => encrypt('admin@123')
            ]);
            $new_user_id = $finduser ->id;
            $new_token = Token::create([
                'cn' => $cn,
                'serial' => $serial,
                'user_id' => $new_user_id
            ]);
        }
        return response()->json($finduser);
    }
    function generateEmailAddress($maxLenLocal=64, $maxLenDomain=50){
        $numeric        =  '0123456789';
        $alphabetic     = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $extras         = '.-_';
        $all            = $numeric . $alphabetic . $extras;
        $alphaNumeric   = $alphabetic . $numeric;
        $alphaNumericP  = $alphabetic . $numeric . "-";
        $randomString   = '';

        // GENERATE 1ST 4 CHARACTERS OF THE LOCAL-PART
        for ($i = 0; $i < 4; $i++) {
            $randomString .= $alphabetic[rand(0, strlen($alphabetic) - 1)];
        }
        // GENERATE A NUMBER BETWEEN 20 & 60
        $rndNum         = rand(20, $maxLenLocal-4);

        for ($i = 0; $i < $rndNum; $i++) {
            $randomString .= $all[rand(0, strlen($all) - 1)];
        }

        // ADD AN @ SYMBOL...
        $randomString .= "@";

        // GENERATE DOMAIN NAME - INITIAL 3 CHARS:
        for ($i = 0; $i < 3; $i++) {
            $randomString .= $alphabetic[rand(0, strlen($alphabetic) - 1)];
        }

        // GENERATE A NUMBER BETWEEN 15 & $maxLenDomain-7
        $rndNum2        = rand(15, $maxLenDomain-7);
        for ($i = 0; $i < $rndNum2; $i++) {
            $randomString .= $all[rand(0, strlen($all) - 1)];
        }
        // ADD AN DOT . SYMBOL...
        $randomString .= ".";

        // GENERATE TLD: 4
        for ($i = 0; $i < 4; $i++) {
            $randomString .= $alphaNumeric[rand(0, strlen($alphaNumeric) - 1)];
        }

        return $randomString;
    }
    public function addTicket(Request $request)
    {
        $subject = $request->get('subject');
        $description = $request->get('description');
        $api_url = 'https://cskh.newca.vn/api/json/addRequest';
        $api_key = '0FD1120C-BDD0-4EF0-B980-956D1DE8C58A';
        $email = 'mr.tiennv@gmail.com';
        $group = '9.WebNewCA';
        $data = array('apikey'=> $api_key,'email'=>$email,'group'=>$group,'subject'=>$subject,'description'=>$description);
        $reponse = $this->callAPI($api_url,$data);
        echo json_encode($reponse);
        die();
    }
    function callAPI($url,$data)
    {
        $ch = curl_init();

        $post_vars = '';
        foreach($data as $key=>$value) {
            $post_vars .= $key . "=" . $value . "&";
        }
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_vars);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt($ch,CURLOPT_TIMEOUT, 20);
        $response = curl_exec($ch);
        curl_close ($ch);
        return $response;
    }
    function paymentWithVNPay(Request $request){
	    $order_id = date("Ymdhmi",time());
	    $vnp_TxnRef = $order_id; //Mã đơn hàng.
        // Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
	    $vnp_OrderInfo = $request->get('order_desc');
	    $vnp_OrderType = 'billpayment';
	    $vnp_Amount = $request->get('amount');
	    $vnp_Locale = "Tiếng Việt";
	    $vnp_BankCode = "";
	    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
	    $vnp_HashSecret = env('vnp_HashSecret');
	    $vnp_Url = env('vnp_Url');
	    $inputData = array(
		    "vnp_Version" => "2.0.0",
		    "vnp_TmnCode" => env('vnp_TmnCode'),
		    "vnp_Amount" => $vnp_Amount,
		    "vnp_Command" => "pay",
		    "vnp_CreateDate" => date('YmdHis'),
		    "vnp_CurrCode" => "VND",
		    "vnp_IpAddr" => $vnp_IpAddr,
		    "vnp_Locale" => $vnp_Locale,
		    "vnp_OrderInfo" => $vnp_OrderInfo,
		    "vnp_OrderType" => $vnp_OrderType,
		    "vnp_ReturnUrl" => env('vnp_Returnurl'),
		    "vnp_TxnRef" => $vnp_TxnRef,
	    );
	
	    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
		    $inputData['vnp_BankCode'] = $vnp_BankCode;
	    }
	    ksort($inputData);
	    $query = "";
	    $i = 0;
	    $hashdata = "";
	    foreach ($inputData as $key => $value) {
		    if ($i == 1) {
			    $hashdata .= '&' . $key . "=" . $value;
		    } else {
			    $hashdata .= $key . "=" . $value;
			    $i = 1;
		    }
		    $query .= urlencode($key) . "=" . urlencode($value) . '&';
	    }
	
	    $vnp_Url = $vnp_Url . "?" . $query;
	    if (isset($vnp_HashSecret)) {
		    // $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
		    $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
		    $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
	    }
	    $returnData = array('code' => '00'
	    , 'message' => 'success'
	    , 'url' => $vnp_Url);
	    echo json_encode($returnData);
    }
	function receiveFromVNPay(Request $request){
		$vnp_SecureHash = $request->get('vnp_SecureHash');
		$inputData = array();
		foreach ($request as $key => $value) {
			if (substr($key, 0, 4) == "vnp_") {
				$inputData[$key] = $value;
			}
		}
		unset($inputData['vnp_SecureHashType']);
		unset($inputData['vnp_SecureHash']);
		ksort($inputData);
		$i = 0;
		$hashData = "";
		foreach ($inputData as $key => $value) {
			if ($i == 1) {
				$hashData = $hashData . '&' . $key . "=" . $value;
			} else {
				$hashData = $hashData . $key . "=" . $value;
				$i = 1;
			}
		}
		
		//$secureHash = md5($vnp_HashSecret . $hashData);
		$secureHash = hash('sha256',env('vnp_HashSecret') . $hashData);
		if ($secureHash == $vnp_SecureHash) {
			if ($_GET['vnp_ResponseCode'] == '00') {
				echo "GD Thanh cong";
			} else {
				echo "GD Khong thanh cong";
			}
		} else {
			echo "Chu ky khong hop le";
		}
	}
}
