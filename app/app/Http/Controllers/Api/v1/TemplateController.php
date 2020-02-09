<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Template\CreateRequest;
use App\Template;
use App\Repositories\ModelRepository;
use App\User;
use Illuminate\Database\QueryException;

class TemplateController extends Controller
{
    protected $model_template;
    protected $model_user;

    public function __construct(Template $template, User $user)
    {
        $this->model_template = new ModelRepository($template);
        $this->model_user = new ModelRepository($user);
    }

    public function get($id)
    {
        $result = ['status' => '400 (Bad Request)', 'message' => '', 'data' => ''];

        try {
            $template = $this->model_template->getById($id);
            $result['data'] = $template;
        } catch (QueryException $ex) {
            $result['message'] = $ex->getMessage();
            return response($result, 400);
        }

        $result['status'] = '200 (Ok)';
        $result['message'] = 'Template retrieved successfully.';
        return response($result, 200);
    }

    public function get_all()
    {
        $result = ['status' => '400 (Bad Request)', 'message' => '', 'data' => ''];
        $result['data'] = $this->model_template->get();
        $result['status'] = '200 (Ok)';
        $result['message'] = 'All Templates retrieved successfully.';
        return response($result, 200);
    }

    public function create(CreateRequest $request)
    {
        $result = ['status' => '400 (Bad Request)', 'message' => '', 'data' => []];
        $user = AuthHelper::instance()->user($request,$this->model_user);

        try {
            $result['data'] = $this->model_template->create(
                [
                    'user_id' => $user->id,
                ]
            );
        } catch (QueryException $ex) {
            $result['message'] = $ex->getMessage();
            return response($result, 400);
        }

        $result['status'] = '200 (Ok)';
        $result['message'] = 'Created template successfully!';
        return response($result, 200);
    }

    public function delete($id)
    {
        $result = ['status' => '400 (Bad Request)', 'message' => '', 'data' => ''];

        try {
            $result['data'] = $this->model_template->deleteById($id);
        } catch (QueryException $ex) {
            $result['message'] = $ex->getMessage();
            return response($result, 400);
        }

        $result['status'] = '200 (Ok)';
        $result['message'] = 'Template deleted successfully';
        return response($result, 200);
    }

}
