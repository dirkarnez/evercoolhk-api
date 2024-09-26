<?php

namespace MyApp\Controller;

use Carbon\Carbon;

use MyApp\Model\User;
use MyApp\Data\UserSessionData;
use MyApp\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use \Illuminate\Database\Capsule\Manager as Capsule;

final class AdministratorController
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @var Session
     */
    private $session;

    /**
     * The constructor.
     *
     * @param Responder $responder The responder
     * @param Session $session The session handler
     */
    public function __construct(Responder $responder, Session $session)
    {
        $this->responder = $responder;
        $this->session = $session;
    }
    
    public function test(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->is_admin()) {
            return $this->responder->unauthorized($response);
        }
        $response->getBody()->write("Hello");
        return $response;
    }
    
    private function is_admin(): bool {
        $userSessionData = $this->session->get("user");
        return !empty($userSessionData->login_id);
    }

    /*
    public function getAllTeachers(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->is_admin()) {
            return $this->responder->unauthorized($response);
        } else {
            return $this->responder->json_string(
                $response,
                Teacher::all()
                    ->map(function ($teacher) {
                        $teacherDTO = new TeacherDTO;
                        $teacherDTO->login_id = $teacher->user->login_id;
                        $teacherDTO->email = $teacher->user->email;
                        $teacherDTO->nickname = $teacher->user->nickname;
                        $teacherDTO->course_name = $teacher->course->name;
                        $teacherDTO->course_id = $teacher->course->id;
                        $teacherDTO->course_exam_id = $teacher->course->exam->id;
                        return $teacherDTO;
                    })
                    ->toJson()
            );
        }
    }

    public function getAllStudents(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->is_admin()) {
            return $this->responder->unauthorized($response);
        } else {
            return $this->responder->json_string(
                $response,
                Student::all()
                    ->map(function ($student) {
                        $studentDTO = new StudentDTO;
                        $studentDTO->login_id = $student->user->login_id;
                        $studentDTO->email = $student->user->email;
                        $studentDTO->nickname = $student->user->nickname;
                        $studentDTO->gender = $student->gender;
                        $studentDTO->course_name = $student->course->name;
                        return $studentDTO;
                    })
                    ->toJson()
            );
        }
    }

    public function addTeacher(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->is_admin()) {
            return $this->responder->unauthorized($response);
        } else {
            try {
                Capsule::beginTransaction();

                $user = $this->saveAsUser($request);
                $data = (array)$request->getParsedBody();
                $teacher = new Teacher;
                $course_id = (int)($data['course_id'] ?? -1);
                if ($course_id > -1) {
                    $teacher->course_id = $course_id;
                }

                $user->teacher()->save($teacher);

                Capsule::commit();

                return $this->responder->ok($response);
            } catch (Exception $e) {
                Capsule::rollBack();
                return $this->responder->internal_server_error($response);
            }
        }
    }

    public function addStudent(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->is_admin()) {
            return $this->responder->unauthorized($response);
        } else {
            try {
                Capsule::beginTransaction();

                $data = (array)$request->getParsedBody();

                $user = $this->saveAsUser($request);

                $student = new Student;
                $student->gender = (string)($data['gender'] ?? '');
                $student->birthday = Carbon::parse((string)($data['birthday'] ?? '')); //Carbon::now();
                $course_id = (int)($data['course_id'] ?? -1);
                if ($course_id > -1) {
                    $student->course_id = $course_id;
                }
                $user->student()->save($student);

                Capsule::commit();

                return $this->responder->ok($response);
            } catch (Exception $e) {
                Capsule::rollBack();
                return $this->responder->internal_server_error($response);
            }
        }
    }

    private function saveAsUser(ServerRequestInterface $request): User {
        $data = (array)$request->getParsedBody();
        $login_id = (string)($data['login_id'] ?? '');
        $password = (string)($data['password'] ?? '');
        $email = (string)($data['email'] ?? '');
        $nickname = (string)($data['nickname'] ?? '');
        $profile_image = $request->getUploadedFiles()['profile_image'];

        $user = new User;
        $user->login_id = $login_id;
        $user->nickname = $nickname;
        $user->email = $email;
        $user->password = $password;
        $user->profile_image = $profile_image->getError() === UPLOAD_ERR_OK ? $profile_image->getStream()->__toString() : "";
        $user->save();

        return $user;
    }

    private function is_admin(): bool {
        $userSessionData = $this->session->get("user");
        return $userSessionData->is_administrator;
    }

    public function getAllTeachers(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->is_admin()) {
            return $this->responder->unauthorized($response);
        } else {
            return $this->responder->json_string(
                $response,
                Teacher::all()
                    ->map(function ($teacher) {
                        $teacherDTO = new TeacherDTO;
                        $teacherDTO->login_id = $teacher->user->login_id;
                        $teacherDTO->email = $teacher->user->email;
                        $teacherDTO->nickname = $teacher->user->nickname;
                        $teacherDTO->course_name = $teacher->course->name;
                        $teacherDTO->course_id = $teacher->course->id;
                        $teacherDTO->course_exam_id = $teacher->course->exam->id;
                        return $teacherDTO;
                    })
                    ->toJson()
            );
        }
    }

    public function getAllStudents(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->is_admin()) {
            return $this->responder->unauthorized($response);
        } else {
            return $this->responder->json_string(
                $response,
                Student::all()
                    ->map(function ($student) {
                        $studentDTO = new StudentDTO;
                        $studentDTO->login_id = $student->user->login_id;
                        $studentDTO->email = $student->user->email;
                        $studentDTO->nickname = $student->user->nickname;
                        $studentDTO->gender = $student->gender;
                        $studentDTO->course_name = $student->course->name;
                        return $studentDTO;
                    })
                    ->toJson()
            );
        }
    }

    public function addTeacher(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->is_admin()) {
            return $this->responder->unauthorized($response);
        } else {
            try {
                Capsule::beginTransaction();

                $user = $this->saveAsUser($request);
                $data = (array)$request->getParsedBody();
                $teacher = new Teacher;
                $course_id = (int)($data['course_id'] ?? -1);
                if ($course_id > -1) {
                    $teacher->course_id = $course_id;
                }

                $user->teacher()->save($teacher);

                Capsule::commit();

                return $this->responder->ok($response);
            } catch (Exception $e) {
                Capsule::rollBack();
                return $this->responder->internal_server_error($response);
            }
        }
    }

    public function addStudent(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->is_admin()) {
            return $this->responder->unauthorized($response);
        } else {
            try {
                Capsule::beginTransaction();

                $data = (array)$request->getParsedBody();

                $user = $this->saveAsUser($request);

                $student = new Student;
                $student->gender = (string)($data['gender'] ?? '');
                $student->birthday = Carbon::parse((string)($data['birthday'] ?? '')); //Carbon::now();
                $course_id = (int)($data['course_id'] ?? -1);
                if ($course_id > -1) {
                    $student->course_id = $course_id;
                }
                $user->student()->save($student);

                Capsule::commit();

                return $this->responder->ok($response);
            } catch (Exception $e) {
                Capsule::rollBack();
                return $this->responder->internal_server_error($response);
            }
        }
    }

    private function saveAsUser(ServerRequestInterface $request): User {
        $data = (array)$request->getParsedBody();
        $login_id = (string)($data['login_id'] ?? '');
        $password = (string)($data['password'] ?? '');
        $email = (string)($data['email'] ?? '');
        $nickname = (string)($data['nickname'] ?? '');
        $profile_image = $request->getUploadedFiles()['profile_image'];

        $user = new User;
        $user->login_id = $login_id;
        $user->nickname = $nickname;
        $user->email = $email;
        $user->password = $password;
        $user->profile_image = $profile_image->getError() === UPLOAD_ERR_OK ? $profile_image->getStream()->__toString() : "";
        $user->save();

        return $user;
    }

    private function is_admin(): bool {
        $userSessionData = $this->session->get("user");
        return $userSessionData->is_administrator;
    }*/

    
    // function getProjects(Request $request, Response &$response, ?int $areaId) {
    //     $collection = [];

    //     $queryParams = $request->getQueryParams();
    //     if (isset($queryParams['random_count'])) {
    //         $randomCount = (int)$queryParams['random_count'];
    //         if ($areaId != null) {
    //             $collection = Project::where('area_id', '=', $areaId)->orderBy(Capsule::raw("RAND()"))->take($randomCount)->get();
    //         } else {
    //             $collection = Project::orderBy(Capsule::raw("RAND()"))->take($randomCount)->get();
    //         }
    //     } else {
    //         if ($areaId != null) {
    //             $collection = Project::where('area_id', '=', $areaId)->get();
    //         } else {
    //             $collection = Project::all();
    //         }
    //     }

    //     try {
    //         $response
    //         ->getBody()
    //         ->write(
    //             json_encode(
    //                 $collection
    //                 ->map(function ($project) {
    //                     $projectDTO = new ProjectDTO;
                        
    //                     $projectDTO->title_chi = $project->title_chi;
    //                     $projectDTO->title_eng = $project->title_eng;
                        
    //                     $projectDTO->client_name_chi = $project->client_name_chi;
    //                     $projectDTO->client_name_eng = $project->client_name_eng;

    //                     $projectDTO->year = $project->year;

    //                     $projectDTO->image_src = $project->image_src;
            
    //                     $projectDTO->area_name_chi = $project->area->name_chi;
    //                     $projectDTO->area_name_eng = $project->area->name_eng;
            
    //                     $projectDTO->created_at = $project->created_at;
    //                     $projectDTO->updated_at = $project->updated_at;
            
    //                     return $projectDTO;
    //                 })
    //             )
    //         );
            
    //         return $response
    //             ->withHeader('Content-Type', 'application/json');
    //     } catch (Exception $e) {
    //         return [];
    //     }
    // }

    // $app->get('/projects', function (Request $request, Response $response, array $arguments): Response {
    //     return getProjects($request, $response, null);
    // });

    // $app->get('/projects/{areaId}', function (Request $request, Response $response, array $arguments): Response {
    //     return getProjects($request, $response, (int)$arguments['areaId']);
    // });

    // $app->get('/areas', function (Request $request, Response $response, array $args) {
    //     try {
    //         $areaList = Area::all();
    //     } catch (Exception $e) {
    //         echo 'Caught exception: ',  $e->getMessage(), "\n";
    //     }

    //     $response->getBody()->write(json_encode($areaList));
    //     return $response
    //         ->withHeader('Content-Type', 'application/json');
    // });

    // $app->post('/upload', function (Request $request, Response $response) {
    //     $directory = __DIR__ . '/uploads';
    //     $uploadedFiles = $request->getUploadedFiles();

    //     $array = array();

    //     // handle single input with multiple file uploads
    //     foreach ($uploadedFiles['example3'] as $uploadedFile) {
    //         if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
    //             $filename = moveUploadedFile($directory, $uploadedFile);
    //             array_push($array, $filename);
    //             //$response->getBody()->write('Uploaded: ' . $filename . '<br/>');
    //         }
    //     }

    //     $response->getBody()->write(json_encode($array));

    //     return $response
    //         ->withHeader('Content-Type', 'application/json');
    // });

    // /**
    //  * Moves the uploaded file to the upload directory and assigns it a unique name
    //  * to avoid overwriting an existing uploaded file.
    //  *
    //  * @param string $directory The directory to which the file is moved
    //  * @param UploadedFileInterface $uploadedFile The file uploaded file to move
    //  *
    //  * @return string The filename of moved file
    //  */
    // function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    // {
    //     $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

    //     // see http://php.net/manual/en/function.random-bytes.php
    //     $basename = bin2hex(random_bytes(8));
    //     $filename = sprintf('%s.%0.8s', $basename, $extension);

    //     $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    //     return $filename;
    // }
}
