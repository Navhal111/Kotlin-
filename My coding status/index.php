<?php
require_once 'vendor/autoload.php';
require_once 'confng.php';
use Slim\Middleware\SessionCookie;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
// include 'php-excel-reader-2.21/excel_reader2.php';
session_start();

$app = new \Slim\App();

// $app->add(new \Slim\Middleware\Session([
//   'name' => 'user',
//   'autorefresh' => true
// ]));

$app->add(new \Slim\Middleware\Session());

$app->get('/', function() {
    echo 'It works!';
    $session = new \SlimSession\Helper;
    $session['user']="admin";
});

$app->get('/edu',function(ServerRequestInterface $request, ResponseInterface $response){
    $conn =sqlConnection();
    try{
      if ($result = $conn->query("SELECT education from education_met where status =1")) {

          while($row = $result->fetch_array()) {
                $myArray[] = $row;
          }
       }
          return $response->withJson($myArray);
          // $resule->insert_id;
    }catch(Exception $e){
          return $e;
      }
      $result->close();
      mysqli_close();
});

$app->get('/occp',function(ServerRequestInterface $request, ResponseInterface $response){
    $conn =sqlConnection();
    try{
      if ($result = $conn->query("SELECT occupation from occupation_met where status =1")) {

          while($row = $result->fetch_array()) {
                $myArray[] = $row;
          }
       }
          return $response->withJson($myArray);
          // $resule->insert_id;
    }catch(Exception $e){
          $msg = array("success"=>0,"msg" => $e);
          return $response->withJson($msg);
      }
      $result->close();
      mysqli_close();
});

$app->get('/logout', function() {
    $session = new \SlimSession\Helper;
    $session->delete('user');
});

$app->get('/admin', function () use ($app) {
      $session = new \SlimSession\Helper;
      if($session->exists('user')){
       echo "admin";
     }else{

       echo "gest";
     }
});

$app->post('/adddata', function(ServerRequestInterface $request, ResponseInterface $response) {

    $conn = getConnection();
    $user_data = $request->getParsedBody();
    $user = json_encode($user_data);
    $json = json_decode($user, true);
  //  var_dump($user_data, json_encode($user_data));

    //print($user_data["name"]);
    $ex = $conn->query("INSERT INTO user(userName, password) VALUES ('".$user_data["name"]."','".$user_data['pass'] ."')");
      /*  foreach($conn->query('SELECT * from user') as $row) {
    print_r(json_encode($row));

      */
    return $user_data["name"];
    $conn = NULL;
});


$app->get('/show', function() {
    $conn = getConnection();
    $m=0;
    foreach($conn->query('SELECT * from user') as $row) {
    $data[$m] = $row;
    $m++;
    }
    $user = json_encode($data);
    $conn = null;
    return $user;
});

$app->get('/showas', function() {

    $conn = sqlConnection();
    $name='1';
    $resule=$conn->prepare("SELECT userName from user where status = ? ");
    if(!$resule){
      echo "error";
    }
    $resule->bind_param('s', $name);
    $resule->execute();
    // $resule->insert_id;
    $resule->bind_result($data);

  while($resule->fetch()){
    echo $data;
  }
   $resule->close();
    mysqli_close($conn);

});

$app->post('/remove', function(ServerRequestInterface $request, ResponseInterface $response) {

    $conn = getConnection();
    $user_data = $request->getParsedBody();
    $user = json_encode($user_data);
    $json = json_decode($user, true);
    //print_r($json["name"]);
    $conn->query("UPDATE user SET status = 0 WHERE userName = '".$json["name"]."'");
    $conn = NULL;
    return "remove";
});
$app->get('/area_json', function($request,$response) {

    $conn = getConnection();

    if($resule=$conn->query("SELECT id,country_name from country where status = 1 ")){
        $i=0;
         while($row = $resule->fetch_assoc()){
           $country_data[$i]['text']= $row['country_name'];
           $country_data[$i]['id'] = $row['id'];
           $i++;

         }

    }
});

// $app->get('/data', function () {
//
//   // $excel = new PhpExcelReader;      // creates object instance of the class
//   // $excel->read('guja_details.xls');
//   // Test to see the excel data stored in $sheets property
//   echo '<pre>';
//   // var_export($excel->sheets);
//   echo '<pre>';
//   $data = new Spreadsheet_Excel_Reader("guja_details.xls");
//   $data->read();
//   //return $excel->sheets;
//
// });

$app->get('/create', function () {
  $con = sqlConnection();
  // "SELECT id,sub_distric from sub_distric where distric_id = ".$distic_data['id'] ." AND ".$distic_data['state_id']." AND ".$distic_data['city_id']." AND ".$distic_data['distric_id']."  AND status = 1 "
  // $resule=$con->prepare("CREATE TABLE profile_met(id int NOT NULL AUTO_INCREMENT,gen_id int,relation varchar(20),profile_name varchar(20) ,surname varchar(20),subcast varchar(20),marital_status varchar(50),gender varchar(10),full_name varchar(50),father_name varchar(30),father_cont varchar(20),mother_name varchar(30),mother_cont varchar(20),living varchar(50),country int,state int,city int,distric int,address varchar(100),dob date,birth_time time,hobby varchar(50),height int,weight int,about varchar(50),education varchar(20),occupation varchar(20),income_status varchar(10),income int,total_income int,email varchar(50),phone_num int(10),block_status tinyint,status tinyint,created_at date,PRIMARY KEY (id))");
  //$resule=$con->prepare("CREATE TABLE family_met(id int NOT NULL AUTO_INCREMENT,profile_id int,father_name varchar(20),father_no int(10),mother_name varchar(20),mother_no int(10),status tinyint,PRIMARY KEY (id))");
  //$resule=$con->prepare("CREATE TABLE sibling_met(id int NOT NULL AUTO_INCREMENT,profile_id int,name varchar(20),gender varchar(10),married_status varchar(10),phone_number int(10),living varchar(20),country int,state int,city int,distric int,sub_distric int,status tinyint,PRIMARY KEY (id))");
  // $resule=$con->prepare("CREATE TABLE request_met(id int NOT NULL AUTO_INCREMENT,sender_id int,receiver_id int,status tinyint,PRIMARY KEY (id))");
  // CREATE TABLE admin_mn(id int NOT NULL AUTO_INCREMENT,email varchar(20),password varchar(20),status tinyint,PRIMARY KEY (id))
  // $resule=$con->prepare("CREATE TABLE city(id int NOT NULL AUTO_INCREMENT,country_id int,state_id int,city varchar(20),status tinyint,PRIMARY KEY (id))");
  // $resule=$con->prepare("CREATE TABLE state(id int NOT NULL AUTO_INCREMENT,country_id int,state varchar(20),status tinyint,PRIMARY KEY (id))");
  // CREATE TABLE sub_admin(id int NOT NULL AUTO_INCREMENT,first_name varchar(20),last_name varchar(20),email varchar(20),password varchar(20),phone_num varchar(10),gender varchar(20),country varchar(20),state varchar(20),city varchar(20),distric varchar(20),sub_distric varchar(20),user_block tinyint,token text,token_exp text,created_at DateTime,status tinyint,PRIMARY KEY (id))
  // CREATE TABLE distric(id int NOT NULL AUTO_INCREMENT,country_id int,state_id int,city_id int,distric varchar(20),status tinyint,PRIMARY KEY (id))
  // CREATE TABLE education_met(id int NOT NULL AUTO_INCREMENT,education varchar(20),status tinyint,PRIMARY KEY (id))
  // CREATE TABLE occupation_met(id int NOT NULL AUTO_INCREMENT,occupation varchar(20),status tinyint,PRIMARY KEY (id))
  // aCREATE TABLE admin_auth(id int NOT NULL AUTO_INCREMENT,admin_id int,matrimony_profile int(3),blood_doner int(3),business_profile int(3),noklari_profile int(3),seva_orgnization int(3),advertisment int(3),corporater int(3),survay_orgnization int(3),aboutus int(3),status tinyint,PRIMARY KEY (id))
  //  CREATE TABLE user(id int NOT NULL AUTO_INCREMENT,first_name varchar(20),last_name varchar(20),email text,password text,phone_num int(10),birth_date text,gender varchar(7),country varchar(20),state varchar(20),city varchar(20),distric varchar(20),sub_distric varchar(20),pincode int,otp_status tinyint,created_at date,token text,token_exp text,payment int,end_date varchar(30),user_block tinyint,status tinyint,PRIMARY KEY (id))
  //  insert into distric(country_id,state_id,city_id,distric) values (1,1,1,'Katargam S.O');
  // CREATE TABLE module_permition(id int NOT NULL AUTO_INCREMENT,sub_admin_id int,role int,matrimony int,register_user int,blood_doner int,noklari int,nokari_user int,seva_orgnization int,bissness_user int,bissness int,servay_ans int,pages int,addvtisement int,corporater int,servay_qus int,status tinyint,PRIMARY KEY (id));
  // CREATE TABLE aria_permition(id int NOT NULL AUTO_INCREMENT,sub_admin_id int,permition_type varchar(20),status tinyint,PRIMARY KEY (id))
  // CREATE TABLE sub_aria_permition(id int NOT NULL AUTO_INCREMENT,area_id int,sub_admin_id int,country varchar(30),state varchar(30),city varchar(30),distric varchar(30),sub_distric varchar(30),status tinyint,PRIMARY KEY (id))
  // sub_admin_id,matrimony,register_user,blood_doner,noklari,nokari_user,seva_orgnization,bissness_user,bissness,servay_ans,pages,addvtisement,corporater,servay_qus
  //  CREATE TABLE role_admin(id int NOT NULL AUTO_INCREMENT,admin_id int,role_id int,status tinyint,PRIMARY KEY (id))
  //CREATE TABLE main_area(id int NOT NULL AUTO_INCREMENT,parent_id int,name varchar(30),PRIMARY KEY (id))
  // CREATE TABLE corporate_details(id int NOT NULL AUTO_INCREMENT,admin_id int,name varchar(30),address text,country int,state int,city int,distric int,sub_diatric varchar(30),phone_num int,phone_num1 int,email int,contact_name varchar(30),block_status tinyint,status tinyint,PRIMARY KEY (id))
  // ALTER TABLE profile_met MODIFY created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL;
  // ALTER TABLE profile_met ALTER COLUMN block_status SET DEFAULT 1;
  // "INSERT INTO sub_admin(first_name,last_name,email,password,phone_num,gender,country,state,city,distric,sub_distric) values ('".$admin_data["first_name"]."','".$admin_data["last_name"]."','".$admin_data["email"]."','".$password."',".$admin_data["phone_num"].",".$admin_data["country"].",".$admin_data["state"].",".$admin_data["city"].",".$admin_data["distric"].",".$admin_data["sub_distric"].")"

// CREATE TABLE request_met(id int NOT NULL AUTO_INCREMENT,RegisterUserId int ,ProfileSenderId int,ProfileReceverId int,status tinyint,PRIMARY KEY (id))
  //  INSERT INTO profile_met(gen_id,relation,profile_name,surname,subcast,marital_status,full_name,father_name,father_cont,mother_name,mother_cont,living,country,state,city,distric,email)values (1,'son','ritesh','navhal','hindu','never maried','navhal ritesh','rites','132546498','adsgfg','60548sffg','surat',1,2,3,4,'light@gmail.com');
  // ALTER TABLE corporate_details ADD COLUMN created_at time AFTER contact_name;
    // CREATE TABLE admin_advtisment(id int NOT NULL AUTO_INCREMENT,admin_id int,title text,discription text,file_name text,country int,state int ,city int,distric int,sub_distric text,email text,phone_num varchar(10),contact_name text,area_per varchar(10),block_status tinyint,status tinyint,created_at date,PRIMARY KEY (id))
    // CREATE TABLE sub_aria_survey(id int NOT NULL AUTO_INCREMENT,survey_id int,sub_admin_id int,country varchar(30),state varchar(30),city varchar(30),distric varchar(30),status tinyint,PRIMARY KEY (id))


// CREATE TABLE profile_met(id int NOT NULL AUTO_INCREMENT,gen_id int,RegisterProfileFor varchar(20),RegisterSurname varchar(20),RegisterSubCast varchar(20),RegisterMaritalStatus varchar(50),RegisterGender varchar(10),RegisterFullname varchar(50),RegisterFatherName varchar(30),RegisterFatherMobile varchar(20),RegisterMotherName varchar(30),RegisterMotherMobile varchar(20),RegisterCountry int,RegisterState int,RegisterCity int, RegisterTaluka int,RegisterAddress varchar(100),RegisterPincode int,RegisterBdate text,RegisterBtime text,RegisterHobby varchar(50),RegisterHeight int,RegisterWeight int,RegisterAboutgroom varchar(50),RegisterEducation varchar(20),RegisterOccupation varchar(20),RegisterIncomeDuration varchar(10),RegisterIncome int,RegisterProfileEmail varchar(50),RegisterProfileMobile int(10),RegisterRefName varchar(20),RegisterRefSurname varchar(20),RegisterRefMobile varchar(10),RegisterRefVillage text,RegisterRefAddress text,RegisterLookingMatrial varchar(20),RegisterLookingEducation int,RegisterLookingSubCast varchar(20),RegisterHoroscope varchar(20),RegisterAboutPartner text,RegisterNRI varchar(20),RegisterAfterMrgJob varchar(20),status tinyint,block_status tinyint,created_at date,PRIMARY KEY (id))

// CREATE TABLE adv_aria_permition(id int NOT NULL AUTO_INCREMENT,area_id int,adv_id int,country int,state int,city int,distric int,status tinyint,PRIMARY KEY (id))

// CREATE TABLE sibling_met(id int NOT NULL AUTO_INCREMENT,profile_id int,name varchar(20),gender varchar(10),married_status varchar(10),phone_number int(10),country int,state int,city int,elder text,status tinyint,PRIMARY KEY (id))
// CREATE TABLE admin_pages(id int NOT NULL AUTO_INCREMENT,admin_id int,title varchar(30),discription text,block_status tinyint,status tinyint,created_at time,PRIMARY KEY (id))
// CREATE TABLE admin_survey(id int NOT NULL AUTO_INCREMENT,sub_admin_id int,question varchar(30),ans varchar(30),area_per varchar(10),country text,state text,city text,distric text,block_status tinyint,status tinyint,created_at date,PRIMARY KEY (id))

// CREATE TABLE admin_survey(id int NOT NULL AUTO_INCREMENT,sub_admin_id int,question varchar(30),ans varchar(30),area_per varchar(10),permition varchar(30),block_status tinyint,status tinyint,created_at date,PRIMARY KEY (id));

// CREATE TABLE adv_aria_permition(id int NOT NULL AUTO_INCREMENT,admin_id int,question text,ans text,country int,state int,city int,,distric int,status tinyint,PRIMARY KEY (id))
// CREATE TABLE brother_met(id int NOT NULL AUTO_INCREMENT,profile_id int,name varchar(20),elder varchar(20),married_status varchar(10),phone_number int(10),education int,occupation int,status_bio varchar(30),status tinyint,PRIMARY KEY (id));
// CREATE TABLE busness_catagery(id int NOT NULL AUTO_INCREMENT,busness_name text,status tinyint,PRIMARY KEY (id));
// CREATE TABLE business_profile(id int NOT NULL AUTO_INCREMENT,CompannyName text,OwnerName text,Address text,BusinessDiscription text,CompnnaylogoName text,CompanylogoString text,Website text,country int ,state int,city int,block_status tinyint,status tinyint,PRIMARY KEY (id));
  // CREATE TABLE Blood_Donation(id int NOT NULL AUTO_INCREMENT,user_id int,DonerName text,BloodGroup text,dob text,DonerWeight text,Gender text,Contact text,Contact_2 text,Email text,Address text,country int,state int,district int,city int,block_status tinyint,status tinyint,PRIMARY KEY (id))
 // CREATE TABLE BusinessCategary_id(id int NOT NULL AUTO_INCREMENT,business_profile int,Categary_id int,PRIMARY KEY (id))

 // CREATE TABLE Blood_requirment(id int NOT NULL AUTO_INCREMENT,user_id int,DonerName text,BloodGroup text,age int,DonerWeight text,Gender text,Contact text,Contact_2 text,Email text,Address text,country int,state int,district int,city int,block_status tinyint,status tinyint,PRIMARY KEY (id))

  // CREATE TABLE Business_time(id int NOT NULL AUTO_INCREMENT,user_id int,business_id int,sun text,mon text,tue text,wed text,thu text,fri text,sat text,PRIMARY KEY (id));
// CREATE TABLE JobPost(id int NOT NULL AUTO_INCREMENT,business_id int,user_id int,JobTitle text,JobType text,Role text,salary_max text,salary_min text,country int ,state int,city int,distric int,Expriance_max text,Expiriance_min text,Discription text,Contact text,Email text,status tinyint,PRIMARY KEY (id))

  // CREATE TABLE BusinessMain_cat(id int NOT NULL AUTO_INCREMENT,business_Name int,PRIMARY KEY (id));
// CREATE TABLE Msg_send(id int NOT NULL AUTO_INCREMENT,MsgTitle text,MsgSubject text,MagBody text,MsgGroup text,area text,Persions text,PRIMARY KEY (id));
// CREATE TABLE Seva_org(id int NOT NULL AUTO_INCREMENT,admin_id int,org_name text,owner_name text,onwe_contact text,owner_email text,org_cat text,discription text,or_add text,country text,state text,distric text,city text,pincode text,status tinyint,block_status tinyint,created_at text,PRIMARY KEY (id));
// CREATE TABLE Nikari_user(id int NOT NULL AUTO_INCREMENT,gen_id int,Name text,Email text,DOB text,Contact text,Education text,Role text,Description text,Adress text,Pincode text,country text,state text ,city text,status tinyint,block_status tinyint,PRIMARY KEY (id));
// CREATE TABLE Job_education(id int NOT NULL AUTO_INCREMENT,profile_id int,degree text,year text,Board text,per text,class text,PRIMARY KEY (id));
    //  $url = 'http://www.mapsofindia.com/pincode/data.php?get=state';
//
    //  $file = fopen("companycview.txt","w");
    //  echo fwrite($file,json_encode($user_data));
    //  fclose($file);
     // $msg = array("success" => 1,'msg'=>'Busness profile created');
     // return $response->withJson(array('response'=>$msg));


  // CREATE TABLE user_servay(id int NOT NULL AUTO_INCREMENT,user_id int,servay_id int,ans text,answer_text text,status tinyint,block_status tinyint,created_at text,PRIMARY KEY (id));
// CREATE TABLE admin_user(id int NOT NULL AUTO_INCREMENT,username varchar(50),password varchar(50),status tinyint,block_status tinyint,PRIMARY KEY (id))
  //  $output = json_encode(file_get_contents($url));
  // $dta=explode("",$dta);
  // echo $output;
  // $enddate=date('Y-m-d H:i:s', strtotime('+184 day'));
  // $message  = "<html><body>";
  //
  // $message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";
  //
  // $message .= "<tr><td>";
  //
  // $message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";
  //
  // $message .= "<thead>
  //    <tr height='80'>
  //     <th colspan='4' style='background-color:#f5f5f5; border-bottom:solid 1px #bdbdbd; font-family:Verdana, Geneva, sans-serif; color:#333; font-size:34px;' >Coding Cage</th>
  //    </tr>
  //    </thead>";
  //
  // $message .= "<tbody>
  //    <tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;'>
  //     <td style='background-color:#00a2d1; text-align:center;'><a href='http://www.codingcage.com/search/label/PDO' style='color:#fff; text-decoration:none;'>PDO</a></td>
  //     <td style='background-color:#00a2d1; text-align:center;'><a href='http://www.codingcage.com/search/label/jQuery' style='color:#fff; text-decoration:none;'>jQuery</a></td>
  //     <td style='background-color:#00a2d1; text-align:center;'><a href='http://www.codingcage.com/search/label/PHP OOP' style='color:#fff; text-decoration:none;' >PHP OOP</a></td>
  //     <td style='background-color:#00a2d1; text-align:center;'><a href='http://www.codingcage.com/search/label/MySQLi' style='color:#fff; text-decoration:none;' >MySQLi</a></td>
  //    </tr>
  //
  //    <tr>
  //     <td colspan='4' style='padding:15px;'>
  //      <p style='font-size:20px;'>Hi' ".$full_name.",</p>
  //      <hr />
  //      <p style='font-size:25px;'>Sending HTML eMail using PHPMailer</p>
  //      <img src='https://4.bp.blogspot.com/-rt_1MYMOzTs/VrXIUlYgaqI/AAAAAAAAAaI/c0zaPtl060I/s1600/Image-Upload-Insert-Update-Delete-PHP-MySQL.png' alt='Sending HTML eMail using PHPMailer in PHP' title='Sending HTML eMail using PHPMailer in PHP' style='height:auto; width:100%; max-width:100%;' />
  //      <p style='font-size:15px; font-family:Verdana, Geneva, sans-serif;'>".$text_message.".</p>
  //     </td>
  //    </tr>
  //
  //    <tr height='80'>
  //     <td colspan='4' align='center' style='background-color:#f5f5f5; border-top:dashed #00a2d1 2px; font-size:24px; '>
  //     <label>
  //     Coding Cage On :
  //     <a href='https://facebook.com/CodingCage' target='_blank'><img style='vertical-align:middle' src='https://cdnjs.cloudflare.com/ajax/libs/webicons/2.0.0/webicons/webicon-facebook-m.png' /></a>
  //     <a href='https://twitter.com/CodingCage' target='_blank'><img style='vertical-align:middle' src='https://cdnjs.cloudflare.com/ajax/libs/webicons/2.0.0/webicons/webicon-twitter-m.png' /></a>
  //     <a href='https://plus.google.com/+PradeepKhodked' target='_blank'><img style='vertical-align:middle' src='https://cdnjs.cloudflare.com/ajax/libs/webicons/2.0.0/webicons/webicon-googleplus-m.png' /></a>
  //     <a href='https://feeds.feedburner.com/cleartutorials' target='_blank'><img style='vertical-align:middle' src='https://cdnjs.cloudflare.com/ajax/libs/webicons/2.0.0/webicons/webicon-rss-m.png' /></a>
  //     </label>
  //     </td>
  //    </tr>
  //
  //    </tbody>";
  //
  // $message .= "</table>";
  //
  // $message .= "</td></tr>";
  // $message .= "</table>";
  //
  // $message .= "</body></html>";
  //  $mail = new PHPMailer(true);
  //  $mail->IsSMTP();
  //  $mail->isHTML(true);
  //  $mail->SMTPDebug  = 0;
  //  $mail->SMTPAuth   = true;
  //  $mail->SMTPSecure = "tls";
  //  $mail->Host       = "smtp.gmail.com";
  //  $mail->Port       = 587;
  //  $mail->AddAddress("ylight528@gmail.com");
  //  $mail->Username   ="milanabdul0@gmail.com";
  //  $mail->Password   ="abmi1234";
  //  $mail->SetFrom('ylight528@gmail.com','App Message');
  //  $mail->AddReplyTo("ylight528@gmail.com","App Massage");
  //  $mail->Subject    = "new massage";
  //  $mail->Body    = $message;
  //  $mail->AltBody    = $message;
  //
  // if($seo=$mail->Send()){
  //   $s="send";
  // }
  if(!$resule){
    echo "error";
  }

  $resule->execute();
  $resule->close();
  mysqli_close($con);


});
// $app->get('/sta_json', function($request,$response,$dat){
//   $username = 'root';
//   $password = 'root';
//   $host = 'localhost';
//   $db = 'aria';
//   $conn = mysqli_connect($host,$username,$password,$db) or die('not conection');
//   $set=2;
//   if($resule=$conn->query("SELECT id,name from countries")){
//        $i=0;
//        while($row = $resule->fetch_assoc()){
//           $a1[$i]['id'] = $row['id'];
//           $a1[$i]['text'] = $row['name'];
//           $conn->query('INSERT INTO m_area(parent_id,name) value (1,"'.$a1[$i]['text'].'")');
//           $country[$i]=array('id'=>$set,'text'=>$a1[$i]['text']);
//           $set1=$set; $set++;
//          if($resule1=$conn->query("SELECT id,name from states where country_id = ".$a1[$i]['id'] )){
//            $j=0;
//             while($row1 = $resule1->fetch_assoc()){
//                 $a2[$j]['id'] = $row1['id'];
//                 $a2[$j]['text']=$row1['name'];
//
//                 $conn->query('INSERT INTO m_area(parent_id,name) value ('.$set1.',"'.$a2[$j]['text'].'")');
//                 $country[$i]['state'][$j]=array('id'=>$set,'text'=>$a2[$j]['text'],'country_id'=>$set1);
//                 $set2=$set; $set++;
//                 if($resule2=$conn->query("SELECT id,name from cities where state_id = ".$a2[$j]['id'])){
//                       $n=0;
//                       while($row2=$resule2->fetch_assoc()){
//                         $a3[$n]['id'] = $row2['id'];
//                         $a3[$n]['text']=$row2['name'];
//                         $conn->query('INSERT INTO m_area(parent_id,name) value ('.$set2.',"'.$a3[$n]['text'].'")');
//                         $country[$i]['state'][$j]['city'][$n]=array('id'=>$set,'text'=>$a3[$n]['text'],'state_id'=>$set2);
//                         $set++;$n++;
//                       }
//
//                 }
//                 $j++;
//          }
//         $i++;
//        }
//
//      }
// }
//   mysqli_close($conn);
//   return $response->withJson(array('aeeay'=>$country));
//
// });
$app->get('/all_json', function($request,$response){
  $conn = sqlConnection();
  if($resule=$conn->query("SELECT id,country_name from country where status = 1 ")){
       $i=0;
       while($row = $resule->fetch_assoc()){
         $a1[$i]['id'] = $row['id'];
         $a1[$i]['text'] = $row['country_name'];
          $country[$i]=array('id'=>$a1[$i]['id'],'text'=>$a1[$i]['text']);
         if($resule1=$conn->query("SELECT id,state from state where country_id = ".$a1[$i]['id'] )){
           $j=0;
            while($row1 = $resule1->fetch_assoc()){
                $a2[$j]['id'] = $row1['id'];
                $a2[$j]['text']=$row1['state'];
                $country[$i]['state'][$j]=array('id'=>$a2[$j]['id'],'text'=>$a2[$j]['text'],'country_id'=>$a1[$i]['id']);
                $j++;
         }
        $i++;
       }

}
}
  $resule->close();
  mysqli_close($conn);
  return $response->withJson($country);

});

$app->get('/sta_json', function($request,$response){
  $conn = sqlConnection();
  $set=2;
  if($resule=$conn->query("SELECT id,country_name from country where status = 1 ")){
       $i=0;
       while($row = $resule->fetch_assoc()){
          $a1[$i]['id'] = $row['id'];
          $a1[$i]['text'] = $row['country_name'];
          $conn->query("INSERT INTO a_main(parent_id,name) value (1,'".$a1[$i]['text']."')");
          $country[$i]=array('id'=>$set,'text'=>$a1[$i]['text']);
          $set1=$set; $set++;
         if($resule1=$conn->query("SELECT id,state from state where country_id = ".$a1[$i]['id'] )){
           $j=0;
            while($row1 = $resule1->fetch_assoc()){
                $a2[$j]['id'] = $row1['id'];
                $a2[$j]['text']=$row1['state'];
                $conn->query("INSERT INTO a_main(parent_id,name) value (".$set1.",'".$a2[$j]['text']."')");
                $country[$i]['state'][$j]=array('id'=>$set,'text'=>$a2[$j]['text'],'country_id'=>$set1);
                $set2=$set; $set++;
                if($resule2=$conn->query("SELECT id,city from city where state_id = ".$a2[$j]['id'])){
                      $n=0;
                      while($row2=$resule2->fetch_assoc()){
                        $a3[$n]['id'] = $row2['id'];
                        $a3[$n]['text']=$row2['city'];
                        $conn->query("INSERT INTO a_main(parent_id,name) value (".$set2.",'".$a3[$n]['text']."')");
                        $country[$i]['state'][$j]['city'][$n]=array('id'=>$set,'text'=>$a3[$n]['text'],'state_id'=>$set2);
                        $set++;$n++;
                      }

                }
                $j++;
         }
        $i++;
       }

     }
}

// $app->get('/busness_catagery_data',function($request,$response,$args){
// $user_data = $request->getParsedBody();

// $sql = 'SELECT id,busness_name FROM busness_catagery';

// $conn =sqlConnection();
// if($result=$conn->query($sql)){
//   while($row=$result->fetch_assoc()){
//         $bussness_data[]=$row;
//   }
// mysqli_close($conn);
// return $response->withJson(array('data'=>$bussness_data));
// }
//      mysqli_close($conn);
//     return $response->withJson(array('success'=>0));

// });
  $resule->close();
  mysqli_close($conn);
  return $response->withJson($country);

});


$app->post('/subadmin', function($request,$response,$arg){
  $admin_data = $request->getParsedBody();
  $cheak = cheak_token($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);

  }
   $conn =sqlConnection();
    return $response->withJson($admin_data);
    if($flg!=1){
      $msg=array('msg'=>'error');
      return $request->withJson($msg);
    }
    $password = md5($admin_data["password"]."key123");
    try{
        if($conn->query("INSERT INTO sub_admin(first_name,last_name,email,password,phone_num,gender,country,state,city,distric,sub_distric) values ('".$admin_data["first_name"]."','".$admin_data["last_name"]."','".$admin_data["email"]."','".$password."',".$admin_data["phone_num"].",".$admin_data["country"].",".$admin_data["state"].",".$admin_data['city'].",".$admin_data["distric"].",".$admin_data["sub_distric"].")")){

                $sub_admin_id = $conn->insert_id;
                    if($conn->query("INSERT INTO module_permition(sub_admin_id,role,matrimony,register_user,blood_doner,noklari,nokari_user,seva_orgnization,bissness_user,bissness,servay_ans,pages,addvtisement,corporater,servay_qus) values (".1.",".$admin_data["sub_admin_id"].",".$admin_data["matrimony"].",".$admin_data["register_user"].",".$admin_data["blood_doner"].",".$admin_data["noklari"].",".$admin_data["nokari_user"].",".$admin_data["seva_orgnization"].",".$admin_data["bissness_user"].",".$admin_data["bissness"].",".$admin_data["sub_distric"].",".$admin_data["servay_ans"].",".$admin_data["pages"].",".$admin_data["addvtisement"].",".$admin_data["corporater"].",".$admin_data["servay_qus"].")")){

                            if($conn->query("INSERT INTO aria_permition(sub_admin_id,permition_type) values (".$admin_data["sub_admin_id"].",'".$admin_data["permition_type"]."')")){
                                     $area_id = $conn->insert_id;

                                     if($admin_data['permition_type']="CO"){
                                       if($conn->query("INSERT INTO sub_admin(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$admin_data["sub_admin_id"].",".$admin_data["country"].",".$admin_data["state"].",".$admin_data["city"].",".$admin_data["distric"].",".$admin_data["sub_distric"].")")){
                                         mysqli_close($conn);
                                         $msg =array('success'=>1,'msg'=>'added sub admin');
                                         return $request->withJson($msg);

                                        }
                                     }

                                     if($admin_data['permition_type']="ST"){
                                       if($conn->query("INSERT INTO sub_admin(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$admin_data["sub_admin_id"].",".$admin_data["country"].",".$admin_data["state"].",".$admin_data["city"].",".$admin_data["distric"].",".$admin_data["sub_distric"].")")){
                                         mysqli_close($conn);
                                         $msg =array('success'=>1,'msg'=>'added sub admin');
                                         return $request->withJson($msg);

                                        }
                                     }
                                     if($admin_data['permition_type']="CT"){
                                       if($conn->query("INSERT INTO sub_admin(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$admin_data["sub_admin_id"].",".$admin_data["country"].",".$admin_data["state"].",".$admin_data["city"].",".$admin_data["distric"].",".$admin_data["sub_distric"].")")){
                                         mysqli_close($conn);
                                         $msg =array('success'=>1,'msg'=>'added sub admin');
                                         return $request->withJson($msg);

                                        }
                                     }
                                     if($admin_data['permition_type']="DT"){
                                       if($conn->query("INSERT INTO sub_admin(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$admin_data["sub_admin_id"].",".$admin_data["country"].",".$admin_data["state"].",".$admin_data["city"].",".$admin_data["distric"].",".$admin_data["sub_distric"].")")){
                                         mysqli_close($conn);
                                         $msg =array('success'=>1,'msg'=>'added sub admin');
                                         return $request->withJson($msg);

                                        }
                                     }
                                     if($admin_data['permition_type']="SD"){
                                       if($conn->query("INSERT INTO sub_admin(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$admin_data["sub_admin_id"].",".$admin_data["country"].",".$admin_data["state"].",".$admin_data["city"].",".$admin_data["distric"].",".$admin_data["sub_distric"].")")){
                                         mysqli_close($conn);
                                         $msg =array('success'=>1,'msg'=>'added sub admin');
                                         return $request->withJson($msg);

                                        }
                                     }


                            }
                     }
         }
    }catch(Exception $e){
       $msg = array('success'=>0,'msg'=>$e);
       return $request->withJson($msg);
    }
    mysqli_close($conn);
    $msg = array('success' => 0,'msg'=>'faild to add');
    return $response->withJson($msg);

});

       // $file = fopen("companycview.txt","w");
       // echo fwrite($file,json_encode($user_data));
       // fclose($file);
$app->run();

?>
