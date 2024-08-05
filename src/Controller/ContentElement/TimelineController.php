<?php

namespace App\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\FilesModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(category: 'texts', template: 'ce_timeline')]
class TimelineController extends AbstractContentElementController
{
    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {

        if ($model->singleSRC) {
            $image = FilesModel::findByUuid($model->singleSRC);

            if ($image) {
                $template->image = $image->path;
            }
        }

        $template->name = $model->name;
        $template->time = $model->time;
        $template->text = $model->text;

        return $template->getResponse();
    }
}
