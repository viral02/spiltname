<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\FileUpload;

class FileController extends Controller

{
        
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return view('welcome');
    }
        
    /**
     * upload
     *
     * @param  mixed $request
     * @return void
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv|max:2048'
        ]);

        $owner = [];

        if($request->has('file')) {
            if (($open = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {
                $num = 0;
                while (($data = fgetcsv($open)) !== FALSE) {
                    if($num == 0){
                        $num++;
                    } else {
                        $people = $this->splitName(trim($data[0]));
                        $peopleArray = [];
                        foreach ($people as $person) {
                            $personArray['title'] = $person['title'];
                            $personArray['first_name'] = ($person['first_name'] ?? '');
                            $personArray['initial'] = ($person['initial'] ?? '');
                            $personArray['last_name'] = ($person['last_name'] ?? '');
                            $peopleArray[] = $personArray;
                        }

                        $owner[] = $peopleArray;
                    }
                }

                fclose($open);
            }
        }

        return response()->json(['success'=>'File uploaded successfully.', 'owner' => $owner]);
    }
    
    /**
     * splitName
     *
     * @param  mixed $name
     * @return void
     */
    public function splitName($name) 
    {
    
        $people = [];
    
        $names = (str_contains($name, 'and')) ? explode(' and ', $name) : $name;
        $names = (str_contains($name, '&')) ? explode(' & ', $name) : $names;
        
        if(!is_array($names)) {
            $names = array($names);
        }
        
        foreach ($names as $name) {
            $person = [];
            
            $nameParts = explode(' ', $name);
            
            // Extract title
            $person['title'] = $nameParts[0];

            if (count($nameParts) == 1) { 
                $val = explode(' ', $names[1]);

                $person['last_name'] = end($val);
            } else {
                $person['last_name'] = implode(' ', array_slice($nameParts, 2));
                
                // Check if the name has a first name or initial
                if (count($nameParts) >= 2) {
                    if (isset($nameParts[1]) && str_contains($nameParts[1], '.')) {
                        $person['initial'] = $nameParts[1];
                        if (count($nameParts) > 2) {
                            $person['last_name'] = implode(' ', array_slice($nameParts, 2));
                        }
                    } else {
                        if(strlen($nameParts[1]) === 1) {
                            $person['initial'] = $nameParts[1];
                            $person['last_name'] = implode(' ', array_slice($nameParts, 2));
                        } else {
                            if(count($nameParts) == 2) {
                                $person['last_name'] = implode(' ', array_slice($nameParts, 1));
                            } else {
                                $person['first_name'] = $nameParts[1];
                                if (count($nameParts) > 2) {
                                    $person['last_name'] = implode(' ', array_slice($nameParts, 2));
                                }
                            }
                        }
                        
                        
                    }
                }
            }
            
            $people[] = $person;
        }
    
        return $people;
    }
}