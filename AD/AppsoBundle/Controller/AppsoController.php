<?php

namespace AD\AppsoBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;

class AppsoController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ADAppsoBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * Renders the top menu, with cache control
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function topMenuAction( $current = '' )
    {
        $rootLocationId = $this->getConfigResolver()->getParameter( 'content.tree_root.location_id' );

        $locationList = $this->buildLocationList( $rootLocationId, 'folder' );

        return $this->render(
            'ADAppsoBundle::page_topmenu.html.twig',
            array(
                'locationList' => $locationList,
                'current' => $current
            ),
            new Response()
        );
    }

    public function subMenuAction( $top, $current )
    {
        $locationList = $this->buildLocationList( $top, 'article' );

        return $this->render(
            'ADAppsoBundle::submenu.html.twig',
            array(
                'submenu' => $locationList,
                'current' => $current,
                'top' => $top
            ),
            new Response()
        );
    }

    public function sliderAction()
    {
        $criteria = array(
            new Criterion\ParentLocationId( 66 ),
            new Criterion\Visibility( Criterion\Visibility::VISIBLE ),
            new Criterion\ContentTypeIdentifier( 'banner' )
        );

        if ( !empty( $criterion ) )
            $criteria[] = $criterion;

        $query = new Query(
            array(
                'criterion' => new Criterion\LogicalAnd( $criteria ),
                'sortClauses' => array( new SortClause\LocationPriority( Query::SORT_ASC ) )
            )
        );

        $result = $this->getRepository()->getSearchService()->findContent( $query );
        $sliders = $this->buildContentListFromSearchResult( $result );

        return $this->render(
            'ADAppsoBundle::slider.html.twig',
            array(
                'sliders' => $sliders
            ),
            new Response()
        );
    }

    public function breadcrumbAction( $pathString )
    {
        $response = new Response(  );
        $repository = $this->getRepository();
        $rootLocationId = $this->getConfigResolver()->getParameter( 'content.tree_root.location_id' );

        $locationService = $repository->getLocationService();
        $locations = explode('/', $pathString);

        $path = array();
        $start = false;
        foreach ($locations as $id) {
            if(!$start){
                if($id == $rootLocationId){
                    $start = true;
                }
            }
            if ($start and !in_array($id, array('', '1'))) {
                $path[] = $locationService->loadLocation($id);
            }
        }

        return $this->render(
            'ADAppsoBundle::page_breadcrumb.html.twig',
            array(
                'path' => $path,
                'pathString' => $pathString
            ),
            $response
        );
    }


    private function buildLocationList( $current, $class )
    {
        $criteria = array(
            new Criterion\ParentLocationId( $current ),
            new Criterion\Visibility( Criterion\Visibility::VISIBLE ),
            new Criterion\ContentTypeIdentifier( $class )
        );

        if ( !empty( $criterion ) )
            $criteria[] = $criterion;

        $query = new Query(
            array(
                'criterion' => new Criterion\LogicalAnd( $criteria ),
                'sortClauses' => array( new SortClause\LocationPriority( Query::SORT_ASC ) )
            )
        );

        $result = $this->getRepository()->getSearchService()->findContent( $query );
        $contentList = $this->buildContentListFromSearchResult( $result );


        $locationList = array();
        // Looping against search results to build $locationList
        // Both arrays will be indexed by contentId so that we can easily refer to an element in a list from another element in the other list
        // See page_topmenu.html.twig
        foreach ($contentList as $contentId => $content) {
            $locationList[$contentId] = $this->getRepository()->getLocationService()->loadLocation($content->contentInfo->mainLocationId);
        }


        /*return $this->render(
            'ADAppsoBundle::page_test.html.twig',
            array(
                'current' => $current,
                'locationList' => $locationList,
                'contentList' => $contentList
            ),
            $response
        );*/

        return $locationList;

    }

    private function buildContentListFromSearchResult( SearchResult $searchResult )
    {
        $contentList = array();
        foreach ( $searchResult->searchHits as $searchHit )
        {
            $contentList[$searchHit->valueObject->contentInfo->id] = $searchHit->valueObject;
        }

        return $contentList;
    }
}
