<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Contacts, User, Catigories, Cities, Properties, propertiesfavoraite};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
  
class Api extends Controller
{

    
    public function postcontact(Request $request){
        $validator = Validator::make($request->all(),[
            'full_name'=>'required',
            'email' => 'required|email|exists:users,email',
            'message'=>'required',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()->all()],401);
        }else{
            // $contact = new Contacts();
            // $contact->full_name = $request->full_name;
            // $contact->email = $request->email;
            // $contact->phone_number = $request->phone_number;
            // $contact->message = $request->message;
            // $contact->save();
            Contacts::create([
                "full_name"=>$request->full_name,
                "email"=>$request->email,
                "message"=>$request->message,
            ]);
            return response()->json(["success"=>"Data Send SuccessFully"],200);
        }
    }

    public function getcontact(){
        $contacts = Contacts::all();
        return response()->json(["contacts"=>$contacts], 200);
    }

    public function updatecontact(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'full_name'=>'required',
            'email'=>'required|email',
            'phone_number'=>'required',
            'message'=>'required',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()->all()],401);
        }else{
            // $contact = Contacts::find($id);
            // $contact->email = $request->email;
            // $contact->phone_number = $request->phone_number;
            // $contact->message = $request->message;
            // $contact->save();
            
            $contact = Contacts::find($id);
            $contact->update([
                "full_name"=>$request->full_name,
                "email"=>$request->email,
                "phone_number"=>$request->phone_number,
                "message"=>$request->message,
            ]);
            return response()->json(["success"=>"Data Update SuccessFully"],200);
        }
    }

    public function deletecontact($id){
        $contact = Contacts::find($id);
        $contact->delete();
        return response()->json(["success"=>"Data Delete SuccessFully"],200);
    }

    public function home(Request $request){
        return response()->json([
            "catigories" => Catigories::latest()->get(),
            "newest" => Properties::with(['catigorey', 'user', 'city'])
            ->ofUser($request->user_id)
            ->ofCatigory($request->catigorey_id)
            ->ofCity($request->city_id)
            ->ofSearch($request->search)
            ->OfPrice($request->price)
            ->ofArea($request->area)
            ->latest()
            ->take(7)
            ->get(),
            'users' => User::latest()->take(7)->get(),
            'popular' => Properties::ofUser($request->user_id)
            ->ofCatigory($request->catigorey_id)
            ->ofCity($request->city_id)
            ->ofSearch($request->search)
            ->OfPrice($request->price)
            ->ofArea($request->area)
            ->latest()
            ->take(7)
            ->orderBy('price',"DESC")
            ->get()
        ], 200);
    }

    public function propertiesUser($id){
        return response()->json([
        "properties"=> Properties::with(['city','catigorey'])->where('user_id', $id)->latest()->get()                
       ],200);
    }
    public function properties(Request $request){
        return response()->json([
        "properties"=> Properties::with(['catigorey', 'user', 'city'])
        ->ofUser($request->user_id)
        ->ofCatigory($request->catigorey_id)
        ->ofCity($request->city_id)
        ->ofSearch($request->search)
        ->OfPrice($request->price)
        ->ofArea($request->area)
        ->latest()
        ->paginate(8)
       ],200);
    }

    public function filterbetween(Request $request){
        return response()->json([
        "properties"=> Properties::with(['catigorey','city'])
        ->OfPrice($request->price)
        ->ofArea($request->area)
        ->latest()
        ->get()
        ],200);
    }
    public function sortPrice(Request $request) {
        return response()->json(['data' => 
        Properties::with(['catigorey', 'user', 'city'])
        ->ofCatigory($request->catigorey_id)
        ->OfPrice($request->price)
        ->ofArea($request->area)
        ->orderByPriceDesc()
        ->paginate(8)
        ],200);
    }
    
    public function allproperties(Request $request){
        return response()->json(["allProperties"=> Properties::with(['catigorey', 'user', 'city'])
        ->ofCity($request->city_id)
        ->ofCatigory($request->catigorey_id)
        ->ofSearch($request->search)
        ->ofCity($request->city_id)
        ->OfPrice($request->price)
        ->ofArea($request->area)
        ->latest()
        ->paginate(50)
    ],200);
    }
    public function property(Request $request){
        // info($request->all());
        $property = Properties::with(['catigorey', 'user', 'city'])->find($request->id);
        if($property){
            return response()->json(['property'=>$property],200);
        }else{
            return response()->json(['Errors'=>"Page Not Found"],401);
        }
    }

    public function users(){
        return response()->json(User::latest()->get(),200 );
    }

    public function user(Request $request,$id){
        $user = User::find($id);
        $user2 = User::find(Auth::id());
        if($user || $user2){
            return response()->json([
                "users"=> $user,
                "user2"=>$user2
            ],200);
        } else {
            return response()->json(['Errors'=>"user Not Found"],401);
        }
    }

    public function changename(Request $request){
        $validtor = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if(!$validtor->fails()){
            $user = User::find(Auth::id());
            $user->name = $request->name;
            $user -> save();
            return response()->json(['Success' => "Name Have Been Changed"]);
        }else{
            return response()->json(['Errors' => $validtor->errors()->all()]);
        }
    }

    public function addnickname(Request $request){
        $validtor = Validator::make($request->all(),[
            'nickname' => 'required'
        ]);

        if(!$validtor->fails()){
            $user = User::find(Auth::id());
            $user->nickname = $request->nickname;
            $user -> save();
            return response()->json(['Success' => "Name Nickname Been Added","user"=>$user]);
        }else{
            return response()->json(['Errors' => $validtor->errors()->all()]);
        }
    }
    public function changeemail(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(!$validator->fails()){
            $user = User::where('email',$request->email)->first();
            if($user){
                $password = Hash::check($request->password, $user->password);
                if($password){
                    return response()->json(["Success"=>"Your Email & Password is True"]);
                }else{
                    return response()->json(["Password"=>"Your Password isn't Match"]);
                }
            }else{
                return response()->json(["Errors"=>"Your Email is invalid"]);
            }
        }else{
            return response()->json(['Errors' => $validator->errors()->all()]);
        }
    }
    public function addnewemail(Request $request){
        $validator = Validator::make($request->all(),[
            'newemail' => 'required|email'
        ]);

        if(!$validator->fails()){
            $user = User::find(Auth::id());
            if($user){
                $user->update([
                    'email' => $request->newemail
                ]);
                return response()->json(["Success"=>"Your Email Have Been Updated"],200);
            }else{
                return response()->json(["Errors"=>"User Not Found"],401);
            }
                
        }else{
            return response()->json(['Errors' => $validator->errors()->all()],401);
        }
    }

    public function login(Request $request){
        $vaildator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
            'password'=>'required'
        ]);
        if(!$vaildator->fails()){

            $user = User::where('email',$request->email)->first();
            if($user){
                if(Hash::check($request->password, $user->password)){
                    return response()->json([
                        'token'=>$user->createToken('authToken')->plainTextToken,
                        'user'=>$user
                    ]);
                }else{
                    return response()->json(['Error' => ['message' => "Invalid email or password", 'code' => 401]], 401);
                }
            }else{
                return response()->json(['Error'=>["User Not Found"]],401);
            }


        }else{
            return response()->json(['Error'=>$vaildator->errors()->all()],401);
        }
    }

    public function logout(Request $request){
       $request->user()->tokens()->delete();
       return response()->json(["success"=>["SuccessFully Logout"]],200);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6',
            'phone_number'=>'required'
        ]);

        if(!$validator->fails()){
            $user = User::create([
               'name' => $request->name,
               'email' => $request->email,
               'password' => Hash::make($request->password),
               'phone_number' => $request->phone_number
           ]);
           event(new Registered($user));

           return response()->json([
                'token' => $user->createToken('authToken')->plainTextToken,
                'user' => $user
           ]);

        }else{
            return response()->json(["Errors"=>$validator->errors()->all()],401);
        }
    }
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->id);

        if ($user->hasVerifiedEmail()) {
            return response()->json(["Message" => ["Email Already Been Verified"]], 200);
        } else {

            if (!hash_equals((string) $request->hash, sha1($user->getEmailForVerification()))) {
                return response()->json(["Message" => ["Invalid verification code"]], 401);
            } else {
                if ($user->markEmailAsVerified()) {
                    event(new Verified($user));
                    return response()->json(["Message" => ["Email verified successfully"]], 200);
                } else {
                    return response()->json(["Message" => ["Email not verified"]], 401);
                }
            }
        }
    }

    public function sendverificationEmail(Request $request){
        if($request->user()->hasVerifiedEmail()){
            return response()->json(["Message" => ["Email Already Been Verified"]], 200);
        }else{
            $request->user()->sendEmailVerificationNotification();
            return response()->json(["Message" => ["Send A New Link To Gmail"]], 200);
        }
    }

    public function forgot(Request $request){
        $validator = Validator::make($request->all(),[
            "email"=>"required|exists:users,email"
        ]);
        if($validator->fails()){
            return response()->json(["Errors"=>$validator->errors()->all()],401);
        }else{
            $status = Password::sendResetLink($request->only('email'));
            if($status == Password::RESET_LINK_SENT){
                return response()->json(["status" => __($status)], 200);
            }else{
                return response()->json(["Faild" => "Faild Send Request To Reset Password"], 200);
            }
        }
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ]);
        if (!$validator->fails()) {

            $status = Password::reset(
                $request->only('email', 'password', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();
                    $user->tokens()->delete();
                    event(new PasswordReset($user));
                }
            );
            if ($status == Password::PASSWORD_RESET) {
                return response()->json(['success' => __('passwords.reset')], 200);
            } else {
                return response()->json(['errors' => [__("Something went wrong !")]], 401);
            }

        } else {
            return response()->json(['errors' => $validator->errors()->all()], 401);
        }
    }

    public function chnagepassword (Request $request) {
        $validtor = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required',
            'newpassword'=>'nullable',
            'phone_number'=>'nullable',
        ]);

        if(!$validtor->fails()){
            $user = User::find(Auth::id());
            if($user->email == $request->email){
                if(Hash::check($request->password,$user->password)){
                    $user->password = $request->newpassword;
                    $user->phone_number = $request->phone_number;
                    $user->save();
                    return response()->json(['Success' => "Change Password SuccessFully"]);
                }else{
                    return response()->json(['ErrorPassword' => "Password is Wrong!"]);
                }
            }else{
                return response()->json(['ErrorEmail' => "Email Not Found!"]);
            }
        }else{
            return response()->json(['Errors' => $validtor->errors()->all()]);
        }
    }

    public function profile(){
        // return $request->user();
        return response()->json( Auth::user(),200);
    }

    public function profileProperties(){
        $properties = Properties::with(['catigorey', 'user', 'city'])
        ->ofUser(Auth::id())
        ->latest()
        ->paginate(10);

        if($properties){
            return response()->json(['Properties' => $properties], 200);
        }else{
            return response()->json(['Errors' => "Properties Not Found"], 401);
        }
    }

    public function delete($id){
        $property = Properties::where([ ['id',$id] , ['user_id',Auth::id()] ])->first();
        if($property){
            $property->delete();
            return response()->json(['Properties' => "Delete Property SuccessFully"], 200);
        }else{
            return response()->json(['Errors' => "Properties Not Found"], 401);
        }
    }

    public function cities(){
        return Cities::all();
    }
    public function catigories(){
        return Catigories::all();
    }
    public function addProperty(Request $request){
        $validator =  Validator::make($request->all(), [
            'catigorey_id' => 'required|exists:catigories,id',
            'city_id' => 'required|exists:cities,id',
            'title' => 'required|min:3',
            'description' => 'required|min:3',
            'price' => 'required|numeric',
            'area' => 'required',
            'bedroom' => 'nullable|numeric',
            'bathroom' => 'nullable|numeric',
            'garage' => 'nullable|numeric',
            'kitchen' => 'nullable|numeric',
            'address' => 'required',
        ]);

        if(!$validator->fails()){

            $property = Properties::create([
                'user_id'=>Auth::id(),
                'catigorey_id'=>$request->catigorey_id,
                'city_id'=>$request->city_id,
                'title'=>$request->title,
                'description'=>$request->description,
                'price'=>$request->price,
                'area'=>$request->area,
                'bedroom'=>$request->bedroom,
                'bathroom'=>$request->bathroom,
                'kitchen'=>$request->kitchen,
                'garage'=>$request->garage,
                'address'=>$request->address,
            ]);

            if($property){
                return response()->json(['Success' => "Property Add SuccessFully",'property' => $property], 200);
            }else{
                return response()->json(['Errors' => "Don't Add Property "], 401);
            }
        }else{
            return response()->json(["Errors" => $validator->errors()->all()], 401);
        }
        
    }
    public function uploadImage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if (!$validator->fails()) {
            $property = properties::where([['user_id', Auth::id()], ['id', $id]])->first();
            if ($property) {
                $filename = time() . rand() . '.' . $request->image->getClientOriginalExtension();
                Image::make($request->image)->save("upload/properties/$filename", 90);
                $images = $property->images;
                $images[] = $filename;
                $property->images = $images;
                $property->save();
                return response()->json(['success' => 'Image uploaded successfully'], 200);
            } else {
                return response()->json(['errors' => 'Property not found'], 401);
            }
        } else {
            return response()->json(['errors' => $validator->errors()->all()], 401);
        }
    }
    public function updateproperty(Request $request, $id){
        $validator =  Validator::make($request->all(), [
            'catigorey_id' => 'nullable|exists:catigories,id',
            'city_id' => 'nullable|exists:cities,id',
            'title' => 'nullable|min:3',
            'description' => 'nullable|min:3',
            'price' => 'nullable|numeric',
            'area' => 'nullable',
            'bedroom' => 'nullable|numeric',
            'bathroom' => 'nullable|numeric',
            'garage' => 'nullable|numeric',
            'kitchen' => 'nullable|numeric',
            'address' => 'nullable',
        ]);

        if(!$validator->fails()){
            $currentproperty = Properties::find($id);
            $currentproperty->update([
                'catigorey_id'=>$request->catigorey_id,
                'city_id'=>$request->city_id,
                'title'=>$request->title,
                'description'=>$request->description,
                'price'=>$request->price,
                'area'=>$request->area,
                'bedroom'=>$request->bedroom,
                'bathroom'=>$request->bathroom,
                'kitchen'=>$request->kitchen,
                'garage'=>$request->garage,
                'address'=>$request->address,
            ]);

            if($currentproperty){
                return response()->json(['Success' => "Property Updated SuccessFully",'property' => $currentproperty], 200);
            }else{
                return response()->json(['Success' => " Property Not Found "], 401);
            }
        }else{
            return response()->json(["Errors" => $validator->errors()->all()], 401);
        }
    }


    // Comments List
    
    public function propertyfavoraite($id){
        $favorite = propertiesfavoraite::with('user')
        ->where('property_id',$id)
        ->whereNotNull('comments')
        ->get();
        return response()->json(['data' => $favorite]);
    }
    // Check Added To favoraite Icaon
    public function checkadded($id){
        $favorite = propertiesfavoraite::where([['property_id', $id],['user_id', Auth::id()]])->get();
        return response()->json(['data' => $favorite]);
    }
    // favoraite List
    public function propertyfavoraite2(){
        $favorite = propertiesfavoraite::where([['user_id',Auth::id()],['isfavoraite',"true"]])->latest()->get();
        return response()->json(['data' => $favorite]);
    }
    // add to Favoraite 
    public function favoraitecreate(Request $request) {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'addtofavoraite' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(["Errors" => $validator->errors()->all()], 401);
        }
    
        $existingRecord = propertiesfavoraite::where([
            'property_id' => $request->property_id,
            'user_id' => Auth::id()
        ])->first();
    
        if ($existingRecord) {
            $existingRecord->update(['isfavoraite' => $request->addtofavoraite]);
            return response()->json(["we Have" => $existingRecord]);
        } else {
            $property = Properties::with(['catigorey', 'user', 'city'])->where('id', $request->property_id)->get();
    
            $propertyfavoraite = propertiesfavoraite::create([
                'user_id' => Auth::id(),
                'property_id' => $request->property_id,
                'property' => $property, 
                'isfavoraite' => $request->addtofavoraite,
            ]);
            if ($propertyfavoraite) {
                return response()->json(['Success' => "Propertyfavoraite Add SuccessFully", 'propertyfavoraite' => $propertyfavoraite], 200);
            } else {
                return response()->json(['Error' => "Failed to Add PropertyFavoraite"], 401);
            }
        }
    }
    // add to Comment 
    public function favoraiteaddcomment(Request $request) {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'comment' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(["Errors" => $validator->errors()->all()], 401);
        }
    
        $existingRecord = propertiesfavoraite::where([
            'property_id' => $request->property_id,
            'user_id' => Auth::id()
        ])->first();
    
        if ($existingRecord) {
            $existingRecord->update(['comments'=>$request->comment]);
            return response()->json(["we Have" => $existingRecord]);
        } else {
            $property = Properties::with(['catigorey', 'user', 'city'])->where('id', $request->property_id)->get();
    
            $propertyfavoraite = propertiesfavoraite::create([
                'user_id' => Auth::id(),
                'property_id' => $request->property_id,
                'property' => $property, 
                'comments'=>$request->comment
            ]);
    
            if ($propertyfavoraite) {
                return response()->json(['Success' => "Propertyfavoraite Add SuccessFully", 'propertyfavoraite' => $propertyfavoraite], 200);
            } else {
                return response()->json(['Error' => "Failed to Add PropertyFavoraite"], 401);
            }
        }
    }
    

    public function addImageProfile (Request $request){

        $validator = Validator::make($request -> all(),[
            'image' => 'required|image|mimes:jepg,jpg,png',
        ]);

        
        if(!$validator -> fails()){

            $user = User::find(Auth::id());

            if($user){
                $filename = time().rand().".".$request->image->getClientOriginalExtension();//1792002389361836135.jep
                Image::make($request->image)->save("upload/users/".$filename ,100);
                $user -> image = $filename;
                $user -> save();
                return  response()->json(['SuccessFull' => "Add Profile Image SuccessFully"]);
            }else{
                return  response()->json(['Erros' => "User Not Found"]);
            }

        }else{
            return response()->json(["Erros" => $validator->errors()->all()]);
        }
    }

    public function testing(Request $request){
        $validator = Validator::make($request -> all(),[
            'image' => 'required|image|mimes:jepg,jpg,png',
        ]);

        $user = User::find(Auth::id());

        if(!$validator -> fails()){
            $filename = time().rand().".".$request->image->getClientOriginalExtension();
            Image::make($request->image)->save("upload/users/".$filename ,80);
            $user -> cover = $filename;
            $user -> save();
            return  response()->json(['SuccessFull' => "Add Cover Image SuccessFully ","user" => $filename]);
        }else{
            return response()->json(["Erros" => $validator->errors()->all()]);
        }
    }

    public function addBio(Request $request) {

        $validator = Validator::make($request -> all(),[
            'bio' => 'required',
        ]);

        
        if(!$validator -> fails()){
            $user = User::find(Auth::id());
            if($user){
                $user->bio = $request->bio;
                $user -> save();
                return  response()->json(['SuccessFull' => "Added Bio SuccessFully ","user" => $user]);
            }else{
                return response()->json(["Erros" => "User Not Found"]);
            }
        }else{
            return response()->json(["Erros" => $validator->errors()->all()]);
        }
    }
}
