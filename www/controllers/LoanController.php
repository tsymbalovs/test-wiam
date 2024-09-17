<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\Loan;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class LoanController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
    
        return $behaviors;
    }

    public function actionCreateRequest()
    {
        $request = Yii::$app->request->getBodyParams();
        
        if (Loan::hasApprovedRequest($request['user_id'])) {
            throw new BadRequestHttpException('User already has an approved request.');
        }

        $loan = new Loan();
        $loan->user_id = $request['user_id'];
        $loan->amount = $request['amount'];
        $loan->term = $request['term'];

        if ($loan->save()) {
            Yii::$app->response->statusCode = 201;

            return [
                'result' => true,
                'id' => $loan->id,
            ];
        } else {
            Yii::$app->response->statusCode = 400;

            return [
                'result' => false,
            ];
        }
    }

    public function actionProcessor($delay = 5)
    {
        $pendingRequests = Loan::findAll(['status' => 'pending']);

        foreach ($pendingRequests as $request) {
            sleep($delay);

            if (Loan::hasApprovedRequest($request->user_id)) {
                $decision = 'declined';
            } else {
                $decision = mt_rand(1, 100) <= 10 ? 'approved' : 'declined';
            }

            $request->status = $decision;
            $request->save(false);
        }

        return [
            'result' => true,
        ];
    }
}
