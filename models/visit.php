<?php

    class Visit{

        private $conn;
        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function getData($type)
        {
            switch ($type)
            {
                case 'sql' :
                    $this->getDataFromDatabase();
                    break;
                case 'json':
                    $this->getDataFromJson();
                    break;
                case 'xml':
                    $this->getDataFromXML();
                    break;
            }
        }

        public function getDataFromDatabase()
        {
            $query = 'SELECT page,COUNT(page) as num FROM visits GROUP BY page';

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            $num = $stmt->rowCount();

            if($num > 0)
            {
                $post_arr = [
                    'error' => 'false',
                    'message' => 'Message 1'
                ];

                $post_arr['data'] = array();

               while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
               {
                   extract($row);
                   $post_item = array(
                       $page => intval($num)
                   );
                   array_push($post_arr['data'],$post_item);

               }

                echo json_encode($post_arr);
            }
            else
            {
                $post_arr = [
                    'error' => 'true',
                    'message' => 'No data'
                ];
                echo json_encode($post_arr);
            }
        }

        public function getDataFromJson()
        {
            $json = file_get_contents('../../data/visits.json');

            $result = json_decode($json,true);


            if($result)
            {
                $post_arr = [
                    'error' => 'false',
                    'message' => 'Message 1'
                ];
                $post_arr['data'] = array();

                foreach ($result['visits'] as $key => $value)
                {
                    $post_item_arr = array(
                        $value['page'] => $value['num']
                    );

                    array_push($post_arr['data'],$post_item_arr);

                }

               echo json_encode($post_arr);
            }
            else
            {
                $post_arr = [
                    'error' => 'true',
                    'message' => 'No data'
                ];
                echo json_encode($post_arr);
            }
        }

        public function getDataFromXML()
        {
            $xml_string = '<visits>
                        <visit>
                            <page>Positive Guys</page>
                            <num>150</num>
                        </visit>
                    </visits>';

            $xml = simplexml_load_string($xml_string);
            $data = json_encode($xml);

            $post_arr = [
                'error' => 'false',
                'message' => 'Message 1'
            ];

            $post_arr['data'] = array();

            $array = json_decode($data);

            if($array)
            {
                foreach ($array as $key => $value)
                {
                    $post_item_arr = array(
                        $value->page => $value->num
                    );

                    array_push($post_arr['data'],$post_item_arr);

                }

                echo json_encode($post_arr);
            }
            else
            {
                echo json_encode(['message' => 'no result']);
            }

        }

        public function getOne($id)
        {
            $query = 'SELECT page,COUNT(*) as num FROM visits WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id',$id);

            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $post_arr = [];
            $post_arr['data'] = array();

            if($result['num'] > 0)
            {
               $post_arr = [
                   'error' => 'false',
                   'message' => 'Visit found',
               ];

               $post_item = array ( $result['page'] => $result['num']);

               $post_arr['data'][] = $post_item;


               echo json_encode($post_arr);
            }
            else
            {
                $post_arr = [
                    'error' => 'true',
                    'message' => 'Not found',
                ];

                echo json_encode($post_arr);
            }
        }

        public function deleteOne($id)
        {
            $query = 'DELETE FROM visits WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id',$id);

            $stmt->execute();

            $post_arr = [
                'error' => 'true',
                'message' => 'Deleted',
            ];

            echo json_encode($post_arr);
        }

        public function create($ip,$page)
        {

            $query = 'INSERT INTO visits 
                        SET ip = :ip,
                            page= :page,
                            created_at = :created_at';

            $stmt = $this->conn->prepare($query);

            $time = time();
            $stmt->bindParam(':ip',$ip);
            $stmt->bindParam(':page',$page);
            $stmt->bindParam(':created_at',$time);

            if($stmt->execute())
            {
                return true;
            }

            return false;

        }

        public function update($id,$data)
        {
            $query = 'UPDATE visits SET ip =:ip AND page =:page WHERE id =:id';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':ip',$data->ip);
            $stmt->bindParam(':page',$data->page);
            $stmt->bindParam(':id',$id);

            if($stmt->execute())
            {
                return true;

            }

            return false;
        }

        public function search($date_start,$date_end)
        {
            $query = 'SELECT page,COUNT(*) as num FROM visits';

            if($date_start && $date_end)
            {
                $date_start = strtotime($date_start);
                $date_end = strtotime($date_end);

                $query.= ' WHERE created_at BETWEEN '.$date_start.' AND '.$date_end;
            }
            $query.=' GROUP BY page';
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            $num = $stmt->rowCount();

            if($num > 0)
            {
                $post_arr = [
                    'error' => 'false',
                    'message' => 'Message 1'
                ];

                $post_arr['data'] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                    extract($row);
                    $post_item = array(
                        $page => intval($num)
                    );
                    array_push($post_arr['data'],$post_item);

                }

                echo json_encode($post_arr);
            }
            else
            {
                $post_arr = [
                    'error' => 'true',
                    'message' => 'No data'
                ];
                echo json_encode($post_arr);
            }


        }

    }
