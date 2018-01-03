<?php


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
  $resule->close();
  mysqli_close($conn);
  return $response->withJson($country);

});


?>
