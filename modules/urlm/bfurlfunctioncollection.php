<?php

class bfURLFunctionCollection
{
    /*!
     Constructor
    */
    function bfURLFunctionCollection()
    {
    }

    static function fetchList( $parameters = array() )
    {
        return self::handleList( $parameters, false );
    }

    static function fetchListCount( $parameters = array() )
    {
        return self::handleList( $parameters, true );
    }

    /*!
     \return all registered URLs.
    */
    static function handleList( $parameters = array(), $asCount = false )
    {

        $parameters = array_merge( array( 'as_object' => true,
                                          'is_valid' => null,
                                          'offset' => false,
                                          'limit' => false,
                                          'only_published' => true,
                                          'filter_by' => '',
                                          'sort_by' => ''
                                          ),
                                   $parameters );
        $asObject = $parameters['as_object'];
        $isValid = $parameters['is_valid'];
        $offset = $parameters['offset'];
        $limit = $parameters['limit'];
        $onlyPublished = $parameters['only_published'];
        $limitArray = null;
        if ( !$asCount and $offset !== false and $limit !== false )
            $limitArray = array( 'offset' => $offset,
                                 'length' => $limit );

        $conditions = array();

        //DJS new filter by url condition
        $filterBy = $parameters['filter_by'];
        if($filterBy != '') {
            $conditions['url'] = '%'.$filterBy.'%';
        }

        //DJS new sort by url
        $sortBy = $parameters['sort_by'];
        $sortQuery = " order by ezurl.modified desc";
        if( $sortBy == 'url') { $sortQuery = " order by ezurl.url"; }     

        if( $isValid === false ) $isValid = 0;
        if ( $isValid !== null )
        {
            $conditions['is_valid'] = $isValid;
        }
        if ( count( $conditions ) == 0 )
            $conditions = null;


        if ( $onlyPublished )  // Only fetch published urls
        {

            $conditionQuery = "";
            if ( $isValid !== null )
            {
                $isValid = (int) $isValid;
                $conditionQuery = " AND ezurl.is_valid=$isValid ";
            }
            
            //DJS new condition field 'url'
            if ( $conditions['url'] != '' )
            {                
                $conditionQuery = " AND ezurl.url like '". $conditions['url'] ."'";
            }

            $db = eZDB::instance();
            $cObjAttrVersionColumn = eZPersistentObject::getShortAttributeName( $db, eZURLObjectLink::definition(), 'contentobject_attribute_version' );

            if ( $asCount )
            {
 
                $urls = $db->arrayQuery( "SELECT count( DISTINCT ezurl.id ) AS count
                                            FROM
                                                 ezurl,
                                                 ezurl_object_link,
                                                 ezcontentobject_attribute,
                                                 ezcontentobject_version
                                            WHERE
                                                 ezurl.id                                     = ezurl_object_link.url_id
                                             AND ezurl_object_link.contentobject_attribute_id = ezcontentobject_attribute.id
                                             AND ezurl_object_link.$cObjAttrVersionColumn     = ezcontentobject_attribute.version
                                             AND ezcontentobject_attribute.contentobject_id   = ezcontentobject_version.contentobject_id
                                             AND ezcontentobject_attribute.version            = ezcontentobject_version.version
                                             AND ezcontentobject_version.status               = " . eZContentObjectVersion::STATUS_PUBLISHED . "
                                                 $conditionQuery" );
                return $urls[0]['count'];
            }
            else
            {
                
                $query = "SELECT DISTINCT ezurl.*
                            FROM
                                  ezurl,
                                  ezurl_object_link,
                                  ezcontentobject_attribute,
                                  ezcontentobject_version
                            WHERE
                                  ezurl.id                                     = ezurl_object_link.url_id
                              AND ezurl_object_link.contentobject_attribute_id = ezcontentobject_attribute.id
                              AND ezurl_object_link.$cObjAttrVersionColumn     = ezcontentobject_attribute.version
                              AND ezcontentobject_attribute.contentobject_id   = ezcontentobject_version.contentobject_id
                              AND ezcontentobject_attribute.version            = ezcontentobject_version.version
                              AND ezcontentobject_version.status               = " . eZContentObjectVersion::STATUS_PUBLISHED . "
                             $conditionQuery $sortQuery";

                 

                if ( !$offset && !$limit )
                {
                    $urlArray = $db->arrayQuery( $query );
                }
                else
                {
                    $urlArray = $db->arrayQuery( $query, array( 'offset' => $offset,
                                                                 'limit'  => $limit ) );
                }
                if ( $asObject )
                {
                    $urls = array();
                    foreach ( $urlArray as $url )
                    {
                        $urls[] = new eZURL( $url );
                    }
                    return $urls;
                }
                else
                    $urls = $urlArray;
                return $urls;
            }
        }
        else
        {
            if ( $asCount )
            {
                $urls = eZPersistentObject::fetchObjectList( eZURL::definition(),
                                                             array(),
                                                             $conditions,
                                                             false,
                                                             null,
                                                             false,
                                                             false,
                                                             array( array( 'operation' => 'count( id )',
                                                                           'name' => 'count' ) ) );
                return $urls[0]['count'];
            }
            else
            {                 
                return eZPersistentObject::fetchObjectList( eZURL::definition(),
                                                            null, $conditions, null, $limitArray,
                                                            $asObject );
            }
        }
    }

}

?>
