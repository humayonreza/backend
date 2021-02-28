<?php
	header('Content-type: application/json');
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
	include('../connect/connect.php');

	$output = array();  
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	$queryId = mysqli_real_escape_string($connect, $request->queryId);  
	$d = date("Y-m-d");
	$currDate = date("Y-m-d", strtotime($d. ' + 0 days'));
	$t= date('H:i:s');
	$currTime = date('H:i:s',strtotime('+8 hour',strtotime($t)));

	switch ($queryId) 
	{
		case "1":
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId); 
			$query = "SELECT menu.Ser as Ser, 		
			menu.MenuId as MenuId,
			menu_lookup.LookupTitle as MenuName,
			menu.MenuDesc as MenuDesc, 
			menu.ImgId as ImgId,
			menu.OrgId as OrgId		 
			FROM menu, menu_lookup 
			WHERE menu.menuId = menu_lookup.LookupValue AND menu_lookup.ParentId = 0 AND menu.OrgId =$OrgId";			
			$result = mysqli_query($connect, $query);  
			if(mysqli_num_rows($result) > 0)  
			{  
				while($row = mysqli_fetch_assoc($result)) 
				{  
					$output[] = $row; 				
				}  
				echo json_encode($output);
			} 
			else {
				echo json_encode("401"); 
			}	
		break;
		case "2":
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId); 
			$query = "SELECT * FROM kitchen_spl WHERE OrgId = $OrgId ORDER BY SER";			
			$result = mysqli_query($connect, $query);  
			if(mysqli_num_rows($result) > 0)  
			{  
				while($row = mysqli_fetch_assoc($result)) 
				{  
					$output[] = $row; 				
				}  
				echo json_encode($output);
			} 
			else {
				echo json_encode("401"); 
			}	
		break;
		case "3":
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId); 
			$MenuId = mysqli_real_escape_string($connect, $request->MenuId); 
			
			$query = "Select item_details.Ser, submenu.ServiceTypeId, submenu.ServiceTypeName, submenu.GenericId, submenu.GenericName, item_details.ItemId, item_details.ItemOriginId, item_details.ItemName, item_details.PrepType, item_details.ItemDesc, item_details.IsOfferExist, item_details.AmountOff, item_details.UnitCost, item_details.Price, item_details.IsExist, item_details.OrgId 
			From menu, submenu,item_details 
			WHERE menu.MenuId = submenu.MenuId AND submenu.ServiceTypeId = item_details.ServiceTypeId
			AND menu.MenuId = $MenuId ORDER BY item_details.Ser";	

			$result = mysqli_query($connect, $query);  
			if(mysqli_num_rows($result) > 0)  
			{  
				while($row = mysqli_fetch_assoc($result)) 
				{  
					$output[] = $row; 				
				}  
				echo json_encode($output);
			} 
			else {
				echo json_encode("401"); 
			}	
		break;
		case "4":
			$Ser = mysqli_real_escape_string($connect, $request->Ser); 
			$MenuId = mysqli_real_escape_string($connect, $request->MenuId); 
			// $MenuName = mysqli_real_escape_string($connect, $request->MenuName); 
			$MenuDesc = mysqli_real_escape_string($connect, $request->MenuDesc); 
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId); 
			
			if ($Ser == 0) {	
				$sql="SELECT Max(Ser) FROM menu";		
				$sql = mysqli_query($connect,$sql) or die(mysqli_error());
				while($row = mysqli_fetch_array($sql))
				{
					$Ser= $row['0'] == 0 ? 1 : $row['0'] + 1;
				}	
				// 
				$sql="SELECT Max(ImgId) FROM menu WHERE OrgId = $OrgId";
				$sql = mysqli_query($connect,$sql) or die(mysqli_error());
				while($row = mysqli_fetch_array($sql))
				{
					$ImgId= $row['0'] == 0 ? 1001 : $row['0'] + 1;
				}
				// ==== Insert Query ===============================
				$query="INSERT INTO menu(Ser,MenuId,MenuDesc,ImgId,OrgId) VALUES($Ser,$MenuId,'$MenuDesc',$ImgId,$OrgId)"; 			
				$resp = mysqli_query($connect, $query) ? "201" : mysqli_error($connect);		
				$response = array('MenuId'=>$MenuId ,'ImgId'=>$ImgId,'CrudCode'=>$resp);
				echo json_encode($response);
			}else {
				$MenuImgId = mysqli_real_escape_string($connect, $request->MenuImgId); 
				$query="UPDATE menu SET
				MenuId = $MenuId,
				MenuDesc = '$MenuDesc'
				WHERE Ser = $Ser AND OrgId = $OrgId"; 
				$resp = mysqli_query($connect, $query) ? "202" : mysqli_error($connect);
				$response = array('Ser'=>$Ser,'CrudCode'=>$resp);
				echo json_encode($response);
			}		
		break;
		// Ser,MenuId,SplMenuId,SplMenuName,SplMenuDesc,UnitCost,Price,IsOfferExist,AmtOffer,OfferEndDate,OrgId
		case "5":
			// Ser,MenuId,SubmenuId,SubmenuName,SubmenuDesc,ImageId,OrgId		
			$Ser = mysqli_real_escape_string($connect, $request->Ser); 
			$MenuId = mysqli_real_escape_string($connect, $request->MenuId); 
			$SplMenuName = mysqli_real_escape_string($connect, $request->SplMenuName); 
			$SplMenuDesc = mysqli_real_escape_string($connect, $request->SplMenuDesc); 
			$UnitCost = mysqli_real_escape_string($connect, $request->SplUnitCost); 
			$Price = mysqli_real_escape_string($connect, $request->SplPrice); 
			$IsOfferExist = mysqli_real_escape_string($connect, $request->IsOfferExist); 
			$AmtOffer = mysqli_real_escape_string($connect, $request->AmtOffer); 
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId); 
			// echo json_encode("Amt ". $AmtOffer);
			if ($Ser == 0) {
				$sql="SELECT Max(Ser) FROM kitchen_spl";		
				$sql = mysqli_query($connect,$sql) or die(mysqli_error());
				while($row = mysqli_fetch_array($sql))
				{
					$Ser= $row['0'] == 0 ? 1 : $row['0'] + 1;
				}	
				// ================
				$sql="SELECT Max(ImgId), Max(SplMenuId) FROM kitchen_spl WHERE OrgId = $OrgId";		
				$sql = mysqli_query($connect,$sql) or die(mysqli_error());
				while($row = mysqli_fetch_array($sql))
				{
					$ImgId= $row['0'] == 0 ? 2001 : $row['0'] + 1;
					$SplMenuId = $row['1'] == 0 ? 1 : $row['1'] + 1;
				}		
				$query="INSERT INTO kitchen_spl(Ser,MenuId,SplMenuId,SplMenuName,SplMenuDesc,UnitCost,Price,IsOfferExist,AmtOffer,ImgId,OrgId) VALUES ($Ser,$MenuId,$SplMenuId,'$SplMenuName','$SplMenuDesc',$UnitCost,$Price,$IsOfferExist,$AmtOffer,$ImgId,$OrgId)"; 
				$resp = mysqli_query($connect, $query) ? "201" : mysqli_error($connect);
				$response = array('Ser'=>$Ser , 'SplMenuId'=>$SplMenuId , 'ImgId'=>$ImgId,'CrudCode'=>$resp);
				echo json_encode($response);
			}else {
				$query="UPDATE kitchen_spl SET
				SplMenuName = '$SplMenuName',
				SplMenuDesc = '$SplMenuDesc',
				UnitCost = $UnitCost,
				Price = $Price,
				IsOfferExist = $IsOfferExist,
				AmtOffer = $AmtOffer WHERE Ser = $Ser AND OrgId = $OrgId"; 
				$resp = mysqli_query($connect, $query) ? "202" : mysqli_error($connect);
				$response = array('Ser'=>$Ser, 'CrudCode'=>$resp);
				// $response="I am inside update..";
				echo json_encode($response);			
			}		
		break;
		case "6":
			// Ser,MenuId,ItemId,ItemName,ItemDesc,ItemOriginId,UnitCost,Price,CategoryId,IsOfferExist,AmtOffer,IsVegetatian,IsAllergic,IsDiscontinued,ImgId,OrgId 		
			$Ser = mysqli_real_escape_string($connect, $request->Ser); 
			$MenuId = mysqli_real_escape_string($connect, $request->MenuId); 
			$ItemName = mysqli_real_escape_string($connect, $request->ItemName); 
			$ItemDesc = mysqli_real_escape_string($connect, $request->ItemDesc); 
			$ItemOriginId = mysqli_real_escape_string($connect, $request->ItemOriginId); 
			$UnitCost = mysqli_real_escape_string($connect, $request->UnitCost); 
			$Price = mysqli_real_escape_string($connect, $request->ItemPrice); 
			$CategoryId = mysqli_real_escape_string($connect, $request->CategoryId); 
			$IsOfferExist = mysqli_real_escape_string($connect, $request->IsOfferExist);
			$AmtOffer = mysqli_real_escape_string($connect, $request->AmtOffer);
			$IsVegetarian = mysqli_real_escape_string($connect, $request->IsVegetarian); 
			$IsAllergic = mysqli_real_escape_string($connect, $request->IsAllergic); 
			$IsFeatured = mysqli_real_escape_string($connect, $request->IsFeatured); 
			$IsDiscontinued = mysqli_real_escape_string($connect, $request->IsDiscontinued); 
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId); 
			$IsGlutonFree = mysqli_real_escape_string($connect, $request->IsGlutonFree);
			$IsEggFree = mysqli_real_escape_string($connect, $request->IsEggFree);
			$IsLactoseFree = mysqli_real_escape_string($connect, $request->IsLactoseFree); 
			$IsSugarFree = mysqli_real_escape_string($connect, $request->IsSugarFree); 
			$IsSeafoodFree = mysqli_real_escape_string($connect, $request->IsSeafoodFree); 
			$IsNutFree = mysqli_real_escape_string($connect, $request->IsNutFree); 

			if ($Ser == 0) {
				$sql="SELECT Max(Ser), Max(ItemId), Max(ImgId) FROM item_details";		
				$sql = mysqli_query($connect,$sql) or die(mysqli_error());
				while($row = mysqli_fetch_array($sql))
				{
					$Ser= $row['0'] == 0 ? 1 : $row['0'] + 1;
					$ItemId = $row['1'] == 0 ? 1 : $row['1'] + 1;
					$ImgId = $row['2'] == 0 ? 1 : $row['2'] + 1;
				}
				$query="INSERT INTO item_details(Ser,MenuId,ItemId,ItemName,ItemDesc,ItemOriginId,UnitCost,Price,CategoryId,IsOfferExist,AmtOffer,IsVegetarian,IsAllergic,IsFeatured,IsDiscontinued,ImgId,OrgId) VALUES ($Ser,$MenuId,$ItemId,'$ItemName','$ItemDesc',$ItemOriginId,$UnitCost,$Price,$CategoryId,$IsOfferExist,$AmtOffer,$IsVegetarian,$IsAllergic,$IsFeatured,$IsDiscontinued,$ImgId,$OrgId)"; 
				$resp = mysqli_query($connect, $query) ? "201" : mysqli_error($connect);
				$response = array('Ser'=>$Ser,'ItemId'=>$ItemId ,'ImgId'=>$ImgId,'CrudCode'=>"201");
				echo json_encode($response);

				$sql="INSERT INTO allergicinfo(Ser,ItemId,IsGlutonFree,IsEggFree,IsLactoseFree,IsSugarFree,IsSeafoodFree,IsNutFree) VALUES ($Ser,$ItemId,$IsGlutonFree,$IsEggFree,$IsLactoseFree,$IsSugarFree,$IsSeafoodFree,$IsNutFree)"; 
				$resp_allergicinfo = mysqli_query($connect, $sql) ? "201" : mysqli_error($connect);
			}else {
				$ItemId = mysqli_real_escape_string($connect, $request->ItemId); 
				$query="UPDATE item_details SET
				ItemName = '$ItemName',
				ItemDesc = '$ItemDesc',
				ItemOriginId = $ItemOriginId,
				UnitCost = $UnitCost,
				Price = $Price,
				CategoryId = $CategoryId,
				IsOfferExist = $IsOfferExist,
				AmtOffer = $AmtOffer,
				IsVegetarian = $IsVegetarian,
				IsAllergic = $IsAllergic,
				IsFeatured = $IsFeatured,
				IsDiscontinued = $IsDiscontinued
				WHERE Ser = $Ser AND OrgId = $OrgId"; 
				$resp = mysqli_query($connect, $query) ? "202" : mysqli_error($connect);
				$response = array('Ser'=>$Ser,'CrudCode'=>$resp);
				echo json_encode($response);
				$ItemId = mysqli_real_escape_string($connect, $request->ItemId); 
				$query="UPDATE allergicinfo SET
				IsGlutonFree = $IsGlutonFree,
				IsEggFree = $IsEggFree,
				IsLactoseFree = $IsLactoseFree,
				IsSugarFree = $IsSugarFree,
				IsSeafoodFree = $IsSeafoodFree,
				IsNutFree = $IsNutFree
				WHERE ItemId = $ItemId"; 
				$resp_allergicinfo = mysqli_query($connect, $query) ? "202" : mysqli_error($connect);
			}		
		break;
		case "7":
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId); 
			$MenuId = mysqli_real_escape_string($connect, $request->MenuId); 
			$query = "SELECT item_details.Ser, item_details.SubmenuId, item_details.ItemId, 
			item_details.ItemName, item_details.ItemDesc, item_details.ItemOriginId, 
			item_details.UnitCost, item_details.Price, item_details.SequenceId, 
			item_details.IsOfferExist, item_details.AmtOffer, item_details.IsGF, 
			item_details.IsHalal, item_details.IsVege, item_details.IsAllergic, 
			item_details.IsDiscontinued, item_details.OrgId FROM submenu, 
			item_details WHERE item_details.SubmenuId = submenu.SubmenuId 
			AND item_details.OrgId = $OrgId AND submenu.MenuId = $MenuId";			
			$result = mysqli_query($connect, $query);  
			if(mysqli_num_rows($result) > 0)  
			{  
				while($row = mysqli_fetch_assoc($result)) 
				{  
					$output[] = $row; 				
				}  
				echo json_encode($output);
			} 
			else {
				echo json_encode("401"); 
			}	
		break;

		case "8":
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId); 
			$query = "SELECT 
			item_details.Ser, 
			item_details.MenuId, 
			item_details.ItemId, 
			item_details.ItemName, 
			item_details.ItemDesc, 
			item_details.ItemOriginId, 
			item_details.UnitCost,   
			item_details.Price, 
			(Price - Price *AmtOffer /100) as SellingPrice,
			item_details.CategoryId, 
			item_details.IsOfferExist, 
			item_details.AmtOffer, 
			item_details.IsVegetarian, 
			item_details.IsAllergic, 
			item_details.IsFeatured, 
			item_details.IsDiscontinued, 
			allergicinfo.IsGlutonFree,
			allergicinfo.IsEggFree,
			allergicinfo.IsLactoseFree,
			allergicinfo.IsSugarFree,
			allergicinfo.IsSeafoodFree,
			allergicinfo.IsNutFree,
			item_details.ImgId ,
			item_details.OrgId 
			FROM item_details, allergicinfo
			WHERE item_details.ItemId = allergicinfo.ItemId 
			AND item_details.OrgId = $OrgId ORDER BY item_details.ItemId";			
			$result = mysqli_query($connect, $query);  
			if(mysqli_num_rows($result) > 0)  
			{  
				while($row = mysqli_fetch_assoc($result)) 
				{  
					$output[] = $row; 				
				}  
				echo json_encode($output);
			} 
			else {
				echo json_encode("401"); 
			}	
		break;
		case "9":
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId); 
			$ParentId = mysqli_real_escape_string($connect, $request->ParentId); 
			$query = "SELECT * FROM menu_lookup WHERE ParentId = $ParentId AND OrgId = $OrgId";  
			$result = mysqli_query($connect, $query);  
			if(mysqli_num_rows($result) > 0)  
			{  
				while($row = mysqli_fetch_assoc($result)) 
				{  
					$output[] = $row; 				
				}  
				echo json_encode($output);
			} 
			else {
				echo json_encode("401"); 
			}	
		break;
		case "10":
			$url = mysqli_real_escape_string($connect, $request->url); 
			$query = "SELECT 
			signin.Ser,
			signin.UserName,
			signin.OrgName,
			signin.OrgEmail,
			signin.OrgId
			FROM signin
			WHERE  OrgLink = '$url'"; 
			$result = mysqli_query($connect, $query);  
			if(mysqli_num_rows($result) > 0)  
			{  
				while($row = mysqli_fetch_assoc($result)) 
				{  
					$output[] = $row; 				
				}  
				echo json_encode($output);
			} 
			else {
				echo json_encode("401"); 
			}	
		break;
		
		echo "Choice Not Found...";
		case "11":
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId); 
			// echo json_encode("201");
			$query = "SELECT * FROM lookup_info WHERE (OrgId = $OrgId AND FlagId = 1) OR FlagId = 0";  
			$result = mysqli_query($connect, $query);  
			if(mysqli_num_rows($result) > 0)  
			{  
				while($row = mysqli_fetch_assoc($result)) 
				{  
					$output[] = $row; 				
				}  
				echo json_encode($output);
			} 
			else {
				echo json_encode("401"); 
			}	
		break;
		case "12":
			// Ser,OrgId,ObjTypeId,ParentId,TxtMain,TxtSm,ImgId,Val,FlagId
			$Ser = mysqli_real_escape_string($connect, $request->Ser);
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId);
			$ObjTypeId = mysqli_real_escape_string($connect, $request->ObjTypeId); 
			$ParentId = mysqli_real_escape_string($connect, $request->ParentId);
			$TxtMain = mysqli_real_escape_string($connect, $request->TxtMain);
			$TxtSm = mysqli_real_escape_string($connect, $request->TxtSm);
			// $ImgId = mysqli_real_escape_string($connect, $request->ImgId);
			// $Val = mysqli_real_escape_string($connect, $request->Val);
			$FlagId = 1;
			// echo json_encode($Ser);
			if ($Ser == 0) {
				$sql="SELECT Max(Ser) FROM lookup_info";		
				$sql = mysqli_query($connect,$sql) or die(mysqli_error());
				while($row = mysqli_fetch_array($sql))
				{
					$Ser= $row['0'] == 0 ? 1 : $row['0'] + 1;
				}
				$sql="SELECT MAX(ImgId) FROM lookup_info WHERE ObjTypeId = $ObjTypeId AND OrgId = $OrgId";		
				$sql = mysqli_query($connect,$sql) or die(mysqli_error());
				while($row = mysqli_fetch_array($sql))
				{
					// $Ser= $row['0'] == 0 ? 1 : $row['0'] + 1;
					$ImgId = $row['0'] == 0 ? 1 : $row['0'] + 1;
					$Val = $row['0'] == 0 ? 1 : $row['0'] + 1;
				}
				$rowcount = 10;
				$query="INSERT INTO lookup_info(Ser,OrgId,ObjTypeId,ParentId,TxtMain,TxtSm,ImgId,Val,FlagId) 
				VALUES ($Ser,$OrgId,$ObjTypeId,$ParentId,'$TxtMain','$TxtSm',$ImgId,$Val,$FlagId)"; 
				$resp = mysqli_query($connect, $query) ? "201" : mysqli_error($connect);
				$response = array('Ser'=>$Ser,'ObjTypeId'=>$ObjTypeId,'ImgId'=>$ImgId,'Val'=>$Val,
				'CrudCode'=>"201");
				echo json_encode($response);
			}else {
				$ItemId = mysqli_real_escape_string($connect, $request->ItemId); 
				$query="UPDATE item_details SET
				ItemName = '$ItemName',
				ItemDesc = '$ItemDesc',
				ItemOriginId = $ItemOriginId,
				UnitCost = $UnitCost,
				Price = $Price,
				CategoryId = $CategoryId,
				IsOfferExist = $IsOfferExist,
				AmtOffer = $AmtOffer,
				IsVegetarian = $IsVegetarian,
				IsAllergic = $IsAllergic,
				IsFeatured = $IsFeatured,
				IsDiscontinued = $IsDiscontinued
				WHERE Ser = $Ser AND OrgId = $OrgId"; 
				$resp = mysqli_query($connect, $query) ? "202" : mysqli_error($connect);
				$response = array('Ser'=>$Ser,'CrudCode'=>$resp);
				echo json_encode($response);
				$ItemId = mysqli_real_escape_string($connect, $request->ItemId); 
				$query="UPDATE allergicinfo SET
				IsGlutonFree = $IsGlutonFree,
				IsEggFree = $IsEggFree,
				IsLactoseFree = $IsLactoseFree,
				IsSugarFree = $IsSugarFree,
				IsSeafoodFree = $IsSeafoodFree,
				IsNutFree = $IsNutFree
				WHERE ItemId = $ItemId"; 
				$resp_allergicinfo = mysqli_query($connect, $query) ? "202" : mysqli_error($connect);
			}
		break;

		case "13":
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId);
			$ObjTypeId = mysqli_real_escape_string($connect, $request->ObjTypeId); 
			// echo json_encode("201");
			$query = "SELECT * FROM lookup_info WHERE OrgId = $OrgId AND ObjTypeId = $ObjTypeId  AND FlagId = 1";  
			$result = mysqli_query($connect, $query);  
			if(mysqli_num_rows($result) > 0)  
			{  
				while($row = mysqli_fetch_assoc($result)) 
				{  
					$output[] = $row; 				
				}  
				echo json_encode($output);
			} 
			else {
				echo json_encode("401"); 
			}	
		break;
		// 
		case "14":
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId);
			// echo json_encode($OrgId); 
			$query = "SELECT * FROM lookup_info WHERE OrgId = $OrgId AND FlagId = 1";  
			$result = mysqli_query($connect, $query);  
			if(mysqli_num_rows($result) > 0)  
			{  
				while($row = mysqli_fetch_assoc($result)) 
				{  
					$output[] = $row; 				
				}  
				echo json_encode($output);
			} 
			else {
				echo json_encode("401"); 
			}	
		break;
		// Ser: formData.Ser,
		// UserType: formData.UserType,
		// UserName: formData.UserName,
		// OrgName: formData.OrgName,
		// OrgEmail: formData.OrgEmail,
		case "15":
			$Ser = mysqli_real_escape_string($connect, $request->Ser);
			$UserType = mysqli_real_escape_string($connect, $request->UserType);
			$UserName = mysqli_real_escape_string($connect, $request->UserName);
			$OrgName = mysqli_real_escape_string($connect, $request->OrgName);
			$OrgEmail = mysqli_real_escape_string($connect, $request->OrgEmail);
			$OrgLink = mysqli_real_escape_string($connect, $request->OrgLink);
			// Ser,UserType,UserId,Password,UserName,OrgName,OrgEmail,CreateDate,SecStr,OrgId
			$FlagId = 1;
			// echo json_encode($Ser);
			if ($Ser == 0) {
				$sql="SELECT Max(Ser),Max(OrgId) FROM signin";		
				$sql = mysqli_query($connect,$sql) or die(mysqli_error());
				while($row = mysqli_fetch_array($sql))
				{
					$Ser= $row['0'] == 0 ? 1 : $row['0'] + 1;
					$OrgId= $row['1'] == 0 ? 1001 : $row['1'] + 1;
				}
				$PassStr = "012(*%3456789abcdefghijklmnopqrstuvwxy$#!zABCDEFGHIJKLMNOPQRSTU@)VWXYZ";
				$ClientPwd = generateRandomString(8,$PassStr);
				$UserIdStr ="0123456789";
				$UserStrRand = generateRandomString(4,$UserIdStr);
				$UserId = ltrim($UserStrRand . $OrgId);
				$UserId = rtrim($UserId);
				$SecStr = "akls@34$";

				$query="INSERT INTO signin(Ser,UserType,UserId,ClientPwd,UserName,OrgName,
				OrgEmail,CreateDate,SecStr,OrgId,OrgLink) 
				VALUES ($Ser,$UserType,'$UserId','$ClientPwd','$UserName','$OrgName',
				'$OrgEmail','$currDate','$SecStr','$OrgId','$OrgLink')"; 
				$resp = mysqli_query($connect, $query) ? "201" : mysqli_error($connect);
				if ($resp && $resp == "201"){
					$response = array('Ser'=>$Ser,'OrgId'=>$UserId,'OrgName'=>$ClientPwd,
					'CrudCode'=>"201");
					echo json_encode($response);
				}else {
					$response = "401";
					echo json_encode($response);
				}
			}else {
				$ItemId = mysqli_real_escape_string($connect, $request->ItemId); 
				$query="UPDATE item_details SET
				ItemName = '$ItemName',
				ItemDesc = '$ItemDesc',
				ItemOriginId = $ItemOriginId,
				UnitCost = $UnitCost,
				Price = $Price,
				CategoryId = $CategoryId,
				IsOfferExist = $IsOfferExist,
				AmtOffer = $AmtOffer,
				IsVegetarian = $IsVegetarian,
				IsAllergic = $IsAllergic,
				IsFeatured = $IsFeatured,
				IsDiscontinued = $IsDiscontinued
				WHERE Ser = $Ser AND OrgId = $OrgId"; 
				$resp = mysqli_query($connect, $query) ? "202" : mysqli_error($connect);
				$response = array('Ser'=>$Ser,'CrudCode'=>$resp);
				echo json_encode($response);
				$ItemId = mysqli_real_escape_string($connect, $request->ItemId); 
				$query="UPDATE allergicinfo SET
				IsGlutonFree = $IsGlutonFree,
				IsEggFree = $IsEggFree,
				IsLactoseFree = $IsLactoseFree,
				IsSugarFree = $IsSugarFree,
				IsSeafoodFree = $IsSeafoodFree,
				IsNutFree = $IsNutFree
				WHERE ItemId = $ItemId"; 
				$resp_allergicinfo = mysqli_query($connect, $query) ? "202" : mysqli_error($connect);
			}
		break;
		case "16":
			$OrgId = mysqli_real_escape_string($connect, $request->OrgId);
			$query = "SELECT * FROM orginfo WHERE OrgId = $OrgId";  
			$result = mysqli_query($connect, $query);  
			if(mysqli_num_rows($result) > 0)  
			{  
				while($row = mysqli_fetch_assoc($result)) 
				{  
					$output[] = $row; 				
				}  
				echo json_encode($output);
			} 
			else {
				echo json_encode("401"); 
			}	
		break;
		// =================
		default:
		echo "Choice Not Found...";
	}

	function generateRandomString($length, $str) {
		$characters = $str;
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	mysqli_close($connect);
?>