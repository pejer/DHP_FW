<?php
declare(encoding = "UTF8");
/**
 * Created by Henrik Pejer ( mr@henrikpejer.com )
 * Date: 2013-07-11 00:12
 */

namespace DHP\modules;
use DHP\blueprint\Module;
use DHP\Response;
use DHP\Request;
use DHP\Routing;

/**
 * Class Propel
 * @package DHP\modules
 *
 * We want this to be a fairly simple interface to a Propel store.
 *
 * We want to be able to let certain uri's be handled by this module.
 *
 * URIs
 * The uris must follow a certain pattern:
 *
 * model/:id
 * Model is the Propel to use, for instance, if we have an author model
 * and we want the post with id 4, the uri should be:
 *
 * author/4
 *
 * IF we have a model using the slug-behaviour, we could use the following
 *
 * author/Joseph-Heller
 *
 * model/page/:pageNumber
 * Here we have a pager query, meaning we want a page of posts from the model.
 * So if we have a author-model, the URI should be
 *
 * author/page/1
 *
 * Example URIs and how they will be interpreted as:
 *
 * author      => author/page/1
 * author/1    => Getting author with id = 1
 * author/page => getting the author with slug 'page'
 *
 *
 * METHODS
 * Different http methods either reads, creates, deletes or updates posts (CRUD!)
 *
 * GET    = read, returns the post and does not change the post
 * PUT    = updates an existing post
 * POST   = creates a new post, will return 201 along with a location to where the
 *          newly created post resides. IF slug-behaviour have been used, the slug
 *          will be used instead of the id ( author/Joseph-Heller)
 * DELETE = Will delete the post found
 *
 *
 * SORTS, GROUPS
 *
 * These kind of commands might be available further down the road. How they will be
 * implemented, have not been considered just yet.
 */
class Propel extends Module
{
    private $response;

    /**
     * @param \DHP\Routing  $routing
     * @param \DHP\Response $response
     * @param \DHP\Request  $request
     * @param String        $uriPrefix the trigger in the URI that this will use. If 'api' then 'api/...' will be handled by this module
     * @param String        $propelConfig the path to the propel config
     * @param String        $propelIncludeDir the include dir of the propel files
     * @param null|String   $propelLibraryDir if the propel library isn't included by the app, this will include it
     * @internal param String $propelDir
     */
    public function __construct(Routing $routing, Response $response,Request $request, $uriPrefix, $propelConfig, $propelIncludeDir, $propelLibraryDir = NULL)
    {
        $this->routing  = $routing;
        $this->response = $response;
        $this->request = $request;
        $that           = $this;
        # setup single post uris
        $this->get($uriPrefix . '/:model/:idOrSlug', function ($model, $idOrSLug = NULL) use ($that) {
            $that->getData($model, $idOrSLug);
        });
        $this->post($uriPrefix . '/:model', function ($model, $idOrSLug = NULL) use ($that) {
            return $that->postData($model);
        });
        $this->post($uriPrefix . '/:model/new', function ($model, $idOrSLug = NULL) use ($that) {
            return $that->postData($model);
        });
        $this->put($uriPrefix . '/:model/:idOrSlug', function ($model, $idOrSLug = NULL) use ($that) {
            return $that->putData($model, $idOrSLug);
        });
        $this->post($uriPrefix . '/:model/:idOrSlug', function ($model, $idOrSLug = NULL) use ($that) {
            return $that->putData($model, $idOrSLug);
        });
        $this->delete($uriPrefix . '/:model/:idOrSlug', function ($model, $idOrSLug = NULL) use ($that) {
            return $that->deleteData($model, $idOrSLug);
        });
        # setup page uris
        $this->get($uriPrefix . '/:model/page/:pageNum', function ($model, $pageNum) use ($that) {
            return $that->pageData($model, $pageNum);
        });
    }

    /**
     * Handles GET requests
     *
     * @param String $model
     * @param null   $idOrSlug
     */
    public function getData($model, $idOrSlug = NULL)
    {
        $model = $this->getPropelQuery($model);
        if (is_numeric($idOrSlug) == TRUE) {
            $data = $model->findPk($idOrSlug);
        } else {
            # todo: this should perhaps not be necessary
            $slug = str_replace(' ', '-', $idOrSlug);
            $data = $model->findBySlug($slug);
        }
        $this->response->setContent($data);
    }

    private function getPropelQuery($model)
    {
        $model         = ucfirst($model);
        $apiClassToUse = '\\espressoTaster\\data\\' . $model . 'Query';
        if (class_exists($apiClassToUse)) {
            return $apiClassToUse::create()->setFormatter('PropelArrayFormatter');
        } else {
            throw new \RuntimeException("Model not found");
        }
    }

    private function getPropelRecord($model){
        $classToUse         = '\\espressoTaster\\data\\' .ucfirst($model);
        if (class_exists($classToUse)) {
            return new $classToUse();
        } else {
            throw new \RuntimeException("Model not found");
        }
    }

    /**
     * Handles POST requests
     *
     * @param String $model
     */
    public function postData($model)
    {
        $object = $this->getPropelRecord($model);
        $object->fromArray($this->request->body);
        $object->save();
        # todo: create correct 201 with location header to API-call for the newly created post, right?
        $this->response->setStatus(201);
    }

    /**
     * Handles PUT requests
     *
     * @param String $model
     * @param null   $idOrSlug
     */
    public function putData($model, $idOrSlug = NULL)
    {
        $object = $this->getPropelQuery($model);
        if ( is_numeric($idOrSlug) ){
            $object->filterByPrimaryKey($idOrSlug);
        } else {
            $object->filterBySlug($idOrSlug);
        }
        if ( $object->count() == 1){
            if ($object->update($this->request->body, null, true) ){
                $post = $object->findOne();
                $this->response->setContent($post);
            } else {
                $this->response->setStatus(500);
            }
        }else{
            $this->response->setStatus(404);
        }
    }

    /**
     * Handles DELETE requests
     *
     * @param String $model
     * @param null   $idOrSlug
     */
    public function deleteData($model, $idOrSlug = NULL)
    {
        $object = $this->getPropelQuery($model);
        if ( is_numeric($idOrSlug) ){
            $object->filterByPrimaryKey($idOrSlug);
        } else {
            $object->filterBySlug($idOrSlug);
        }
        if ( $object->count() == 1 && $object->delete()){
            $this->response->setStatus(204);
        }else{
            $this->response->setStatus(404);
        }

    }

    /**
     * Handles page requests
     *
     * @param String $model
     * @param int    $pageNum the page to get, defaults to first page
     */
    public function pageData($model, $pageNum = 1)
    {
        #$this->response->setContent("Should get page {$pageNum} for {$model}");
        if ( $pageNum < 1 ){
            $pageNum = 1;
        }
        $model = $this->getPropelQuery($model);
        # todo: 10, below, should be configurable
        $pageData = $model->paginate($pageNum, 10);
        $data     = array('data' => array(), 'pagination' => array('numberOfPages' => $pageData->getLastPage(), 'numberOfHits' => $pageData->getNbResults()));
        if ($pageNum <= $pageData->getlastPage()) {
            foreach ($pageData as $post) {
                $data[] = $post;
            }
        }
        $this->response->setContent($data);
    }
}