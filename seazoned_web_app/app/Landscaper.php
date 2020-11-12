<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Landscaper extends Model
{

    public $timestamps = false;

    public function addLawnMawning($request = array(), $landscaper_id = "", $service_id = "")
    {

        $acre_limit = $request->mow_acre_limit / 0.25;
        $first_acre = $request->mow_first_acre;
        $first_grass = $request->mow_first_grass;
        $next_acre = $request->mow_next_acre;
        $next_grass = $request->mow_next_grass;
        $price = 0;
        $data["service_table"] = [];
        for ($i = 1; $i <= $acre_limit; $i++) {
            $acre = 0.25 * $i;
            if ($i == 1)
                $price = $first_acre;
            else
                $price += $next_acre;

            $data["service_table"][$i]["service_id"] = $service_id;
            $data["service_table"][$i]["service_field_value"] = ($acre - 0.25) . ' - ' . $acre;
            $data["service_table"][$i]["service_field_price"] = $price;
            $data["service_table"][$i]["service_field_id"] = 1;
            $data["service_table"][$i]["landscaper_id"] = $landscaper_id;
        }

        DB::table('service_details')->insert($data["service_table"]);

        unset($data);

        $data = [[
            "service_id" => $service_id,
            "service_field_value" => "0 - 6",
            "service_field_price" => $first_grass,
            "service_field_id" => 2,
            "landscaper_id" => $landscaper_id
        ],
            ["service_id" => $service_id,
                "service_field_value" => ">6",
                "service_field_price" => $next_grass + $first_grass,
                "service_field_id" => 2,
                "landscaper_id" => $landscaper_id]
        ];

        DB::table('service_details')->insert($data);
    }

    public function addLawnTreatment($request = array(), $landscaper_id = "", $service_id = "")
    {

        $acre_limit = $request->lawn_acre_limit / 0.25;
        $first_acre = $request->lawn_first_acre;
        $next_acre = $request->lawn_next_acre;
        $price = 0;
        $data["service_table"] = [];

        for ($i = 1; $i <= $acre_limit; $i++) {
            $acre = 0.25 * $i;
            if ($i == 1)
                $price = $first_acre;
            else
                $price += $next_acre;

            $data["service_table"][$i]["service_id"] = $service_id;
            $data["service_table"][$i]["service_field_value"] = ($acre - 0.25) . ' - ' . $acre;
            $data["service_table"][$i]["service_field_price"] = $price;
            $data["service_table"][$i]["service_field_id"] = 1;
            $data["service_table"][$i]["landscaper_id"] = $landscaper_id;
        }

        DB::table('service_details')->insert($data["service_table"]);
    }

    public function addSprinklerWinterizing($request, $landscaper_id, $service_id)
    {
        $price = 0;
        $data = [];

        if($request->win_acre_limit!=""){

            $acre_limit = $request->win_acre_limit / 0.25;
            $first_acre = $request->win_first_acre;
            $next_acre = $request->win_next_acre;            

            for ($i = 1; $i <= $acre_limit; $i++) {
                $acre = 0.25 * $i;
                if ($i == 1)
                    $price = $first_acre;
                else
                    $price += $next_acre;

                $data[$i]["service_id"] = $service_id;
                $data[$i]["service_field_value"] = ($acre - 0.25) . ' - ' . $acre;
                $data[$i]["service_field_price"] = $price;
                $data[$i]["service_field_id"] = 1;
                $data[$i]["landscaper_id"] = $landscaper_id;
            }

            DB::table('service_details')->insert($data);


            // unset($data);
        }
           // $data = [];

        if($request->win_zone_limit!=""){

            $zone_limit = floor($request->win_zone_limit / 3);            
            $first_zone = $request->win_first_zone;            
            $next_zone = $request->win_next_zone;

            for ($i = 1; $i <= $zone_limit; $i++) {
                if ($i == 1)
                    $price = $first_zone;
                else
                    $price += $next_zone;

                $data[$i]["service_id"] = $service_id;
                $data[$i]["service_field_value"] = (($i * 3) - 3) . ' - ' . ($i * 3);
                $data[$i]["service_field_price"] = $price;
                $data[$i]["service_field_id"] = 11;
                $data[$i]["landscaper_id"] = $landscaper_id;
            }

            DB::table('service_details')->insert($data);
        }
    }

    public function addAeration($request = array(), $landscaper_id = "", $service_id = "")
    {

        $acre_limit = $request->aera_acre_limit / 0.25;
        $first_acre = $request->aera_first_acre;
        $next_acre = $request->aera_next_acre;
        $price = 0;
        $data["service_table"] = [];

        for ($i = 1; $i <= $acre_limit; $i++) {
            $acre = 0.25 * $i;
            if ($i == 1)
                $price = $first_acre;
            else
                $price += $next_acre;

            $data["service_table"][$i]["service_id"] = $service_id;
            $data["service_table"][$i]["service_field_value"] = ($acre - 0.25) . ' - ' . $acre;
            $data["service_table"][$i]["service_field_price"] = $price;
            $data["service_table"][$i]["service_field_id"] = 1;
            $data["service_table"][$i]["landscaper_id"] = $landscaper_id;
        }

        DB::table('service_details')->insert($data["service_table"]);
    }

    public function addPoolCleaning($request = array(), $landscaper_id = "", $service_id = "")
    {
        $chlorine = $request->chlorine;
        $saline = $request->saline;

        $spa_hot_tub = $request->spa_hot_tub;

        $inground = $request->inground;
        $above_ground = $request->above_ground;

        $clear = $request->clear;
        $cloudy = $request->cloudy;
        $heavy = $request->heavy;

        $data = [array(
            'landscaper_id' => $landscaper_id,
            'service_id' => $service_id,
            'service_field_id' => 7,
            'service_field_value' => 'Chlorine',
            'service_field_price' => $chlorine,
        ), array(
            'landscaper_id' => $landscaper_id,
            'service_id' => $service_id,
            'service_field_id' => 7,
            'service_field_value' => 'Saline',
            'service_field_price' => $saline,
        ), array(
            'landscaper_id' => $landscaper_id,
            'service_id' => $service_id,
            'service_field_id' => 8,
            'service_field_value' => 'Yes',
            'service_field_price' => $spa_hot_tub,
        ), array(
            'landscaper_id' => $landscaper_id,
            'service_id' => $service_id,
            'service_field_id' => 8,
            'service_field_value' => 'No',
            'service_field_price' => 0,
        ), array(
            'landscaper_id' => $landscaper_id,
            'service_id' => $service_id,
            'service_field_id' => 9,
            'service_field_value' => 'Inground',
            'service_field_price' => $inground,
        ), array(
            'landscaper_id' => $landscaper_id,
            'service_id' => $service_id,
            'service_field_id' => 9,
            'service_field_value' => 'Above Ground',
            'service_field_price' => $above_ground,
        ), array(
            'landscaper_id' => $landscaper_id,
            'service_id' => $service_id,
            'service_field_id' => 10,
            'service_field_value' => 'Relatively Clear',
            'service_field_price' => $clear,
        ), array(
            'landscaper_id' => $landscaper_id,
            'service_id' => $service_id,
            'service_field_id' => 10,
            'service_field_value' => 'Moderately Cloudy',
            'service_field_price' => $cloudy,
        ), array(
            'landscaper_id' => $landscaper_id,
            'service_id' => $service_id,
            'service_field_id' => 10,
            'service_field_value' => 'Heavy Algae Present',
            'service_field_price' => $heavy,
        )];

        DB::table('service_details')->insert($data);
    }

    public function addLeafRemoval($request = array(), $landscaper_id = "", $service_id = "")
    {

        $acre_limit = $request->leaf_acre_limit / 0.25;
        $first_acre = $request->leaf_first_acre;
        $next_acre = $request->leaf_next_acre;

        $light = $request->leaf_light;
        $medium = $request->leaf_medium;
        $heavy = $request->leaf_heavy;
        $over_top = $request->leaf_over_top;

        $price = 0;
        $data["service_table"] = [];
        for ($i = 1; $i <= $acre_limit; $i++) {
            $acre = 0.25 * $i;
            if ($i == 1)
                $price = $first_acre;
            else
                $price += $next_acre;

            $data["service_table"][$i]["service_id"] = $service_id;
            $data["service_table"][$i]["service_field_value"] = ($acre - 0.25) . ' - ' . $acre;
            $data["service_table"][$i]["service_field_price"] = $price;
            $data["service_table"][$i]["service_field_id"] = 1;
            $data["service_table"][$i]["landscaper_id"] = $landscaper_id;
        }

        DB::table('service_details')->insert($data["service_table"]);

        unset($data);

        $data = [
            array(
                'landscaper_id' => $landscaper_id,
                'service_id' => $service_id,
                'service_field_id' => 6,
                'service_field_value' => 'Light',
                'service_field_price' => $light,
            ), array(
                'landscaper_id' => $landscaper_id,
                'service_id' => $service_id,
                'service_field_id' => 6,
                'service_field_value' => 'Medium',
                'service_field_price' => $medium,
            ), array(
                'landscaper_id' => $landscaper_id,
                'service_id' => $service_id,
                'service_field_id' => 6,
                'service_field_value' => 'Heavy',
                'service_field_price' => $heavy,
            ), array(
                'landscaper_id' => $landscaper_id,
                'service_id' => $service_id,
                'service_field_id' => 6,
                'service_field_value' => 'Over the top',
                'service_field_price' => $over_top,
            )
        ];

        DB::table('service_details')->insert($data);
    }

    public function addSnowRemoval($request = array(), $landscaper_id = "", $service_id = "")
    {

        $car_limit = $request->car_limit / 2;
        $first_car = $request->first_car;
        $next_car = $request->next_car;

        $driveway_type = [
            'Straight' => $request->straight,
            'Circular' => $request->circular,
            'Incline' => $request->incline
        ];

        $service_type = [
            'Front Door Walk Way' => $request->front_door,
            'Stairs and Front Landing' => $request->stairs,
            'Side Door Walk Way' => $request->side_door
        ];

        $price = 0;
        $data["service_table"] = [];
        for ($i = 1; $i <= $car_limit; $i++) {
            $car = 2 * $i;
            if ($i == 1)
                $price = $first_car;
            else
                $price += $next_car;

            $data["service_table"][$i]["service_id"] = $service_id;
            $data["service_table"][$i]["service_field_value"] = $car;
            $data["service_table"][$i]["service_field_price"] = $price;
            $data["service_table"][$i]["service_field_id"] = 3;
            $data["service_table"][$i]["landscaper_id"] = $landscaper_id;
        }

        DB::table('service_details')->insert($data["service_table"]);

        unset($data);

        foreach ($driveway_type as $value => $price) {
            $data[] = array(
                'landscaper_id' => $landscaper_id,
                'service_id' => $service_id,
                'service_field_id' => 4,
                'service_field_value' => $value,
                'service_field_price' => $price,
            );
        }

        DB::table('service_details')->insert($data);

        unset($data);

        foreach ($service_type as $value => $price) {
            $data[] = array(
                'landscaper_id' => $landscaper_id,
                'service_id' => $service_id,
                'service_field_id' => 5,
                'service_field_value' => $value,
                'service_field_price' => $price,
            );
        }

        DB::table('service_details')->insert($data);
    }

    public function addServiceDays($request = array(), $landscaper_id = "", $service_id = "")
    {
        $days = $request->days;
        $start = $request->start;
        $end = $request->end;

        foreach ($days as $key => $day) {
            if ($start[$key] != "" && $end[$key] != "") {
                $data[] = array(
                    'landscaper_id' => $landscaper_id,
                    'service_id' => $service_id,
                    'service_day' => $day,
                    'start_time' => date('H:i:s',strtotime($start[$key])),
                    'end_time' => date('H:i:s',strtotime($end[$key])),
                );
            }
        }

        DB::table('service_times')->insert($data);
    }

    public function addRecurringServices($request = array(), $landscaper_id = "", $service_id = "")
    {
        $recurring_services = ['Every 7 days', 'Every 10 days', 'Every 14 days', 'Just Once'];
        $rec_services = $request->recurring_services[$service_id];

        foreach ($recurring_services as $index => $val) {
            $data[] = array(
                'landscaper_id' => $landscaper_id,
                'service_id' => $service_id,
                'service_frequency' => $val,
                'discount_price' => $rec_services[$index]
            );
        }

        DB::table('service_prices')->insert($data);
    }

    public function updateRecurringServices($request = array(), $landscaper_id = "", $service_id = "")
    {
        $recurring_services = ['Every 7 days', 'Every 10 days', 'Every 14 days', 'Just Once'];
        $rec_services = $request->recurring_services[$service_id];

        foreach ($recurring_services as $index => $val) {
            
            DB::table('service_prices')
                    ->where('landscaper_id', $landscaper_id)
                    ->where('service_id', $service_id)
                    ->where('service_frequency', $val)
                    ->update(['discount_price'=>$rec_services[$index]]);
        }
        
    }
    
    
    
    
    
    public function LandscaperDetails()
    {
        try {
            $select = 'SELECT * FROM landscapers';
            $data = DB::select($select);
            return $data;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function getLoadServiceNew($id)
    {
        $response = [];
        $data = Landscaper::where([["user_id", "=", session("user_id")], ["service_id", "=", $id]])->get();
        if (count($data) == 0) {
            return "No Services Found";
        } else {
            if ($data[0]->profile_image == NULL) {
                $response["profile_image"] = url("default/images/lands-mowing.jpg");
            } else {
                $response["profile_image"] = url("uploads/services/" . $data[0]->profile_image);
            }
            $response["landscaper_id"] = Landscaper::where([["user_id", "=", session("user_id")], ["service_id", "=", $id]])->get()[0]->id;
            switch ($id) {
                case 1:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '1';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["acre"] = DB::select("select * from service_details where service_id = '1' and landscaper_id = '{$data[0]->id}' and service_field_id = '1';");
                    $x["grass"] = DB::select("select * from service_details where service_id = '1' and landscaper_id = '{$data[0]->id}' and service_field_id = '2';");
                    $x['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = '{$data[0]->id}' AND service_id = '1'");
                    $response["form"] = base64_encode(view("ajax_pages.services.lawn-mawning", $x));
                    break;
                case 2:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '2';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["acre"] = DB::select("select * from service_details where service_id = '2' and landscaper_id = '{$data[0]->id}' and service_field_id = '1';");
                    $x["leaf"] = DB::select("select * from service_details where service_id = '2' and landscaper_id = '{$data[0]->id}' and service_field_id = '6';");
                    $x['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = '{$data[0]->id}' AND service_id = '2'");
                    $response["form"] = base64_encode(view("ajax_pages.services.leaf-removal", $x));
                    break;
                case 3:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '3';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["acre"] = DB::select("select * from service_details where service_id = '3' and landscaper_id = '{$data[0]->id}' and service_field_id = '1';");
                    $x['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = '{$data[0]->id}' AND service_id = '3'");
                    $response["form"] = base64_encode(view("ajax_pages.services.lawn-treatment", $x));
                    break;
                case 4:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '4';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["acre"] = DB::select("select * from service_details where service_id = '4' and landscaper_id = '{$data[0]->id}' and service_field_id = '1';");
                    $x['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = '{$data[0]->id}' AND service_id = '4'");
                    $response["form"] = base64_encode(view("ajax_pages.services.aeration", $x));
                    break;
                case 5:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '5';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["acre"] = DB::select("select * from service_details where service_id = '5' and landscaper_id = '{$data[0]->id}' and service_field_id = '1';");
                    $x["zone"] = DB::select("select * from service_details where service_id = '5' and landscaper_id = '{$data[0]->id}' and service_field_id = '11';");
                    $x['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = '{$data[0]->id}' AND service_id = '5'");
                    $response["form"] = base64_encode(view("ajax_pages.services.sprinkler_winterizing", $x));
                    break;
                case 6:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '6';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["spa"] = DB::select("select * from service_details where service_id = '6' and landscaper_id = '{$data[0]->id}' and service_field_id = '8';");
                    $x["water_type"] = DB::select("select * from service_details where service_id = '6' and landscaper_id = '{$data[0]->id}' and service_field_id = '7';");
                    $x["pool_type"] = DB::select("select * from service_details where service_id = '6' and landscaper_id = '{$data[0]->id}' and service_field_id = '9';");
                    $x["pool_state"] = DB::select("select * from service_details where service_id = '6' and landscaper_id = '{$data[0]->id}' and service_field_id = '10';");
                    $x['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = '{$data[0]->id}' AND service_id = '6'");
                    $response["form"] = base64_encode(view("ajax_pages.services.pool-cleaning", $x));
                    break;
                case 7:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '7';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["car_number"] = DB::select("select * from service_details where service_id = '7' and landscaper_id = '{$data[0]->id}' and service_field_id = '3';");
                    $x["road_type"] = DB::select("select * from service_details where service_id = '7' and landscaper_id = '{$data[0]->id}' and service_field_id = '4';");
                    $x["where"] = DB::select("select * from service_details where service_id = '7' and landscaper_id = '{$data[0]->id}' and service_field_id = '5';");
                    $x['service_prices'] = DB::select("SELECT * FROM service_prices WHERE landscaper_id = '{$data[0]->id}' AND service_id = '7'");
                    $response["form"] = base64_encode(view("ajax_pages.services.snow_removal", $x));
                    break;
            }
            return $response;
        }
    }
    
    public function getServiceHours(){
        $user_id = session("user_id");
        $data = Landscaper::where("user_id", "=", $user_id)->get();
         if(!$data->isEmpty()){
        $service_hours = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '{$data[0]->service_id}';");
        return $service_hours;
         }else{
             return '';
         }
    }

    public function getLoadService($id)
    {
        $response = [];
        $data = Landscaper::where([["user_id", "=", session("user_id")], ["service_id", "=", $id]])->get();
        if (count($data) == 0) {
            return "No Services Found";
        } else {
            if ($data[0]->profile_image == NULL) {
                $response["profile_image"] = url("default/images/lands-mowing.jpg");
            } else {
                $response["profile_image"] = url("uploads/services/" . $data[0]->profile_image);
            }
            $response["landscaper_id"] = Landscaper::where([["user_id", "=", session("user_id")], ["service_id", "=", $id]])->get()[0]->id;
            switch ($id) {
                case 1:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '1';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["acre"] = DB::select("select * from service_details where service_id = '1' and landscaper_id = '{$data[0]->id}' and service_field_id = '1';");
                    $x["grass"] = DB::select("select * from service_details where service_id = '1' and landscaper_id = '{$data[0]->id}' and service_field_id = '2';");
                    $response["form"] = base64_encode(view("ajax_pages.services.lawn-mawning", $x));
                    break;
                case 2:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '2';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["acre"] = DB::select("select * from service_details where service_id = '2' and landscaper_id = '{$data[0]->id}' and service_field_id = '1';");
                    $x["leaf"] = DB::select("select * from service_details where service_id = '2' and landscaper_id = '{$data[0]->id}' and service_field_id = '6';");
                    $response["form"] = base64_encode(view("ajax_pages.services.leaf-removal", $x));
                    break;
                case 3:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '3';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["acre"] = DB::select("select * from service_details where service_id = '3' and landscaper_id = '{$data[0]->id}' and service_field_id = '1';");
                    $response["form"] = base64_encode(view("ajax_pages.services.lawn-treatment", $x));
                    break;
                case 4:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '4';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["acre"] = DB::select("select * from service_details where service_id = '4' and landscaper_id = '{$data[0]->id}' and service_field_id = '1';");
                    $response["form"] = base64_encode(view("ajax_pages.services.aeration", $x));
                    break;
                case 5:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '5';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["acre"] = DB::select("select * from service_details where service_id = '5' and landscaper_id = '{$data[0]->id}' and service_field_id = '1';");
                    $x["zone"] = DB::select("select * from service_details where service_id = '5' and landscaper_id = '{$data[0]->id}' and service_field_id = '11';");
                    $response["form"] = base64_encode(view("ajax_pages.services.sprinkler_winterizing", $x));
                    break;
                case 6:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '6';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["spa"] = DB::select("select * from service_details where service_id = '6' and landscaper_id = '{$data[0]->id}' and service_field_id = '8';");
                    $x["water_type"] = DB::select("select * from service_details where service_id = '6' and landscaper_id = '{$data[0]->id}' and service_field_id = '7';");
                    $x["pool_type"] = DB::select("select * from service_details where service_id = '6' and landscaper_id = '{$data[0]->id}' and service_field_id = '9';");
                    $x["pool_state"] = DB::select("select * from service_details where service_id = '6' and landscaper_id = '{$data[0]->id}' and service_field_id = '10';");
                    $response["form"] = base64_encode(view("ajax_pages.services.pool-cleaning", $x));
                    break;
                case 7:
                    $service_time = DB::select("select * from service_times where landscaper_id = '{$data[0]->id}' and service_id = '7';");
                    $response["service_time"] = base64_encode(view("ajax_pages.landscaper.service-timing", ["service_time" => $service_time]));
                    $x["car_number"] = DB::select("select * from service_details where service_id = '7' and landscaper_id = '{$data[0]->id}' and service_field_id = '3';");
                    $x["road_type"] = DB::select("select * from service_details where service_id = '7' and landscaper_id = '{$data[0]->id}' and service_field_id = '4';");
                    $x["where"] = DB::select("select * from service_details where service_id = '7' and landscaper_id = '{$data[0]->id}' and service_field_id = '5';");
                    $response["form"] = base64_encode(view("ajax_pages.services.snow_removal", $x));
                    break;
            }
            return json_encode($response);
        }
    }
    
    public function match_password($current_password, $user_id)
    {
            $sql = DB::select("SELECT * FROM users WHERE id = '{$user_id}';");
            $current_password1=$sql[0]->password;
            
            $current_password2=md5($current_password);
            if($current_password1==$current_password2)
            {
		return 1;
            }
            else
            {
                return 0;
            }
    }
    
    public function get_favorite($landscaper_id){       
        
         $data['user_id'] = session("user_id");
         $data['landscaper_id'] = $landscaper_id;
         $res = DB::select("SELECT * from favorite_landscapers where user_id=".session('user_id') .' AND landscaper_id = '.$data['landscaper_id']);
         if(count($res)>0)
            return $res[0]->visible;
         else
            return 0;
    }
    public function get_all_rating($landscaper_id){
        $data["landscapper_info"] = DB::select("select * from landscapers where id = '$landscaper_id'");
          $user_id = $data['landscapper_info'][0]->user_id;
          
        $data = [];
        $qry5 = DB::select("SELECT landscaper_id from service_ratings WHERE initiated_by !=".$user_id ." AND landscaper_id = ".$landscaper_id." AND rating_value = 5 ");
        $data[5]= count($qry5);
        $qry4 = DB::select("SELECT landscaper_id from service_ratings WHERE initiated_by !=".$user_id ." AND landscaper_id = ".$landscaper_id." AND rating_value = 4 ");
        $data[4]= count($qry4);
        $qry3 = DB::select("SELECT landscaper_id from service_ratings WHERE initiated_by !=".$user_id ." AND landscaper_id = ".$landscaper_id." AND rating_value = 3 ");
        $data[3]= count($qry3);
        $qry2 = DB::select("SELECT landscaper_id from service_ratings WHERE initiated_by !=".$user_id ." AND landscaper_id = ".$landscaper_id." AND rating_value = 2 ");
        $data[2]= count($qry2);
        $qry1 = DB::select("SELECT landscaper_id from service_ratings WHERE initiated_by !=".$user_id ." AND landscaper_id = ".$landscaper_id." AND rating_value = 1 ");
        $data[1]= count($qry1);
        
        return $data;
        
    }
    
     public function get_overall_rating($landscaper_id){
         
          $result = [];
          $data["landscapper_info"] = DB::select("select * from landscapers where id = '$landscaper_id'");
          $user_id = $data['landscapper_info'][0]->user_id;
          $qry = DB::select("SELECT AVG(rating_value) as average_rating  FROM `service_ratings` WHERE initiated_by !=".$user_id ." AND landscaper_id =".$landscaper_id);
          $res  = $qry[0]->average_rating;
          $result= round($res,1); 
          return $result;
     }
    
     public function get_total_rating_count($landscaper_id){
         $data["landscapper_info"] = DB::select("select * from landscapers where id = '$landscaper_id'");
         $user_id = $data['landscapper_info'][0]->user_id;
         $qry = DB::select("SELECT rating_value FROM `service_ratings` WHERE initiated_by !=".$user_id ." AND landscaper_id =".$landscaper_id);
         $res = count($qry);
         return $res;
         
     }
     
     
     public function get_total_review_count($landscaper_id){
         $data["landscapper_info"] = DB::select("select * from landscapers where id = '$landscaper_id'");
         $user_id = $data['landscapper_info'][0]->user_id;
         $qry1 = DB::select("SELECT COUNT(review) as review FROM `service_ratings` WHERE initiated_by !=".$user_id ." AND landscaper_id =".$landscaper_id);
         $res1 = $qry1[0]->review;
         return $res1;
         
     }
     
     
     public function get_user_overall_rating($user_id){
         
          $result = [];
          $qry = DB::select("SELECT AVG(rating_value) as average_rating  FROM `service_ratings` WHERE initiated_by !=".$user_id ." AND customer_id =".$user_id);
          $res  = $qry[0]->average_rating;
          $result= round($res); 
          
          return $result;
     }
     
    public function get_service_rating_details($landscaper_id)
    { 
        $data["landscapper_info"] = DB::select("select * from landscapers where id = '$landscaper_id'");
        $user_id = $data['landscapper_info'][0]->user_id;
        $qry = DB::select("SELECT sr.rating_value,sr.review,sr.log_time,ud.first_name,ud.last_name,ud.profile_image FROM  service_ratings sr,user_details ud WHERE sr.customer_id = ud.user_id AND sr.initiated_by !=".$user_id." AND sr.landscaper_id = " . $landscaper_id);

        return $qry;
    }
    
    public function get_landscaper_service_images($landscaper_id)
    { 
        $data["landscapper_info"] = DB::select("select * from landscapers where id = '$landscaper_id'");
        $user_id = $data['landscapper_info'][0]->user_id;
        $qry = DB::select("SELECT service_image FROM service_images WHERE uploaded_by=".$user_id);

        return $qry;
    }
    
}
