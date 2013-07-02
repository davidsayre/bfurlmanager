<?php

/* Enhancements:
* filterBy param from input
* call bfURLFunctionCollection::fetchList() instead of eZurl
*/

$Module = $Params['Module'];
$ViewMode = $Params['ViewMode'];
$UserParameters = $Params['UserParameters'];
$http = eZHTTPTool::instance();
if( eZPreferences::value( 'admin_urlm_list_limit' ) )
{
    switch( eZPreferences::value( 'admin_urlm_list_limit' ) )
    {
        case '2': { $limit = 25; } break;
        case '3': { $limit = 50; } break;
        case '4': { $limit = 100; } break;
        default:  { $limit = 10; } break;
    }
}
else
{
    $limit = 10;
}

$filterBy = $UserParameters['filter_by']; //from pagination

if ( $http->hasPostVariable( 'filterBy' ) ){    //from form post 
    $filterBy = $http->postVariable( 'filterBy' );
}

$sortBy = $UserParameters['sort_by']; //from pagination

$offset = $Params['Offset'];
if ( !is_numeric( $offset ) )
{
    $offset = 0;
}

if( $ViewMode != 'all' && $ViewMode != 'invalid' && $ViewMode != 'valid')
{
    $ViewMode = 'all';
}

if ( $Module->isCurrentAction( 'SetValid' ) )
{
    $urlSelection = $Module->actionParameter( 'URLSelection' );
    eZURL::setIsValid( $urlSelection, true );
}
else if ( $Module->isCurrentAction( 'SetInvalid' ) )
{
    $urlSelection = $Module->actionParameter( 'URLSelection' );
    eZURL::setIsValid( $urlSelection, false );
}


if( $ViewMode == 'all' )
{        
    $listParameters = array( 'is_valid'       => null,
                             'offset'         => $offset,
                             'limit'          => $limit,
                             'only_published' => true,
                             'filter_by'  => $filterBy,
                             'sort_by' => $sortBy );

    $countParameters = array( 'only_published' => true,
                             'filter_by'  => $filterBy );
}
elseif( $ViewMode == 'valid' )
{
    $listParameters = array( 'is_valid'       => true,
                             'offset'         => $offset,
                             'limit'          => $limit,
                             'only_published' => true,
                             'filter_by'  => $filterBy,
                             'sort_by' => $sortBy );

    $countParameters = array( 'is_valid' => true,
                              'only_published' => true,
                             'filter_by'  => $filterBy );
}
elseif( $ViewMode == 'invalid' )
{
    $listParameters = array( 'is_valid'       => false,
                             'offset'         => $offset,
                             'limit'          => $limit,
                             'only_published' => true,
                             'filter_by'  => $filterBy,
                             'sort_by' => $sortBy);

    $countParameters = array( 'is_valid' => false,
                              'only_published' => true,
                              'filter_by'  => $filterBy );
}

$list = bfURLFunctionCollection::fetchList( $listParameters ); // DJS use custom
$listCount = bfURLFunctionCollection::fetchListCount( $countParameters );

$viewParameters = array( 'offset' => $offset, 'limit'  => $limit, 'filter_by' => $filterBy, 'sort_by' => $sortBy );


$tpl = eZTemplate::factory();

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'url_list', $list );
$tpl->setVariable( 'url_list_count', $listCount );
$tpl->setVariable( 'view_mode', $ViewMode );
$tpl->setVariable( 'filter_by', $filterBy );
$tpl->setVariable( 'sort_by', $sortBy );

$Result = array();
$Result['content'] = $tpl->fetch( "design:url/list.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/url', 'URL' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/url', 'List' ) ) );
?>
