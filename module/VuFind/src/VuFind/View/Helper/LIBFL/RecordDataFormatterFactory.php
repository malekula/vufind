<?php
/**
 * Factory for record driver data formatting view helper
 *
 * PHP version 5
 *
 * Copyright (C) Villanova University 2016.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category VuFind
 * @package  View_Helpers
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:architecture:record_data_formatter
 * Wiki
 */
namespace VuFind\View\Helper\LIBFL;

/**
 * Factory for record driver data formatting view helper
 *
 * @category VuFind
 * @package  View_Helpers
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org/wiki/development:architecture:record_data_formatter
 * Wiki
 */
class RecordDataFormatterFactory
{
    /**
     * Create the helper.
     *
     * @return RecordDataFormatter
     */
    public function __invoke()
    {
        $helper = new RecordDataFormatter();
        $helper->setDefaults(
            'collection-info', [$this, 'getDefaultCollectionInfoSpecs']
        );
        $helper->setDefaults(
            'collection-record', [$this, 'getDefaultCollectionRecordSpecs']
        );
        $helper->setDefaults('core', [$this, 'getDefaultCoreSpecs']);
        $helper->setDefaults('description', [$this, 'getDefaultDescriptionSpecs']);
        $helper->setDefaults('libfl_description', [$this, 'getLibflDescriptionSpecs']);
        $helper->setDefaults('libfl_exemplar', [$this, 'getLibflExemplarSpecs']);
        $helper->setDefaults('brit', [$this, 'getDefaultBjvvvSpecs']);
        $helper->setDefaults('sovet', [$this, 'getDefaultBjvvvSpec']);
        $helper->setDefaults('bjacc', [$this, 'getDefaultBjvvvSpecs']);
        $helper->setDefaults('bjvvv', [$this, 'getDefaultBjvvvSpecs']);
        $helper->setDefaults('bjscc', [$this, 'getDefaultBjvvvSpecs']);
        $helper->setDefaults('bjfcc', [$this, 'getDefaultBjvvvSpecs']);
        $helper->setDefaults('redkostj', [$this, 'getDefaultBjvvvSpecs']);
        $helper->setDefaults('litres', [$this, 'getDefaultLitresSpecs']);
        $helper->setDefaults('period', [$this, 'getDefaultPeriodSpecs']);
        return $helper;
    }

    /**
     * Get default specifications for displaying data in collection-info metadata.
     *
     * @return array
     */
    public function getDefaultCollectionInfoSpecs()
    {
        $spec = new RecordDataFormatter\SpecBuilder();
        $spec->setTemplateLine(
            'Main Authors', 'getDeduplicatedAuthors', 'data-authors.phtml',
            [
                'useCache' => true,
                'labelFunction' => function ($data) {
                    return count($data['main']) > 1
                        ? 'Main Authors' : 'Main Author';
                },
                'context' => ['type' => 'main', 'schemaLabel' => 'author'],
            ]
        );
        $spec->setTemplateLine(
            'Corporate Authors', 'getDeduplicatedAuthors', 'data-authors.phtml',
            [
                'useCache' => true,
                'labelFunction' => function ($data) {
                    return count($data['corporate']) > 1
                        ? 'Corporate Authors' : 'Corporate Author';
                },
                'context' => ['type' => 'corporate', 'schemaLabel' => 'creator'],
            ]
        );
        $spec->setTemplateLine(
            'Other Authors', 'getDeduplicatedAuthors', 'data-authors.phtml',
            [
                'useCache' => true,
                'context' => [
                    'type' => 'secondary', 'schemaLabel' => 'contributor'
                ],
            ]
        );
        $spec->setLine('Summary', 'getSummary');
        $spec->setLine(
            'Format', 'getFormats', 'RecordHelper',
            ['helperMethod' => 'getFormatList']
        );
        $spec->setLine('Language', 'getLanguages');
        $spec->setTemplateLine(
            'Published', 'getPublicationDetails', 'data-publicationDetails.phtml'
        );
        $spec->setLine(
            'Edition', 'getEdition', null,
            ['prefix' => '<span property="bookEdition">', 'suffix' => '</span>']
        );
        $spec->setTemplateLine('Series', 'getSeries', 'data-series.phtml');
        $spec->setTemplateLine(
            'Subjects', 'getAllSubjectHeadings', 'data-allSubjectHeadings.phtml'
        );
        $spec->setTemplateLine('Online Access', true, 'data-onlineAccess.phtml');
        $spec->setTemplateLine(
            'Related Items', 'getAllRecordLinks', 'data-allRecordLinks.phtml'
        );
        $spec->setLine('Notes', 'getGeneralNotes');
        $spec->setLine('Production Credits', 'getProductionCredits');
        $spec->setLine('ISBN', 'getISBNs');
        $spec->setLine('ISSN', 'getISSNs');
        return $spec->getArray();
    }

    /**
     * Get default specifications for displaying data in collection-record metadata.
     *
     * @return array
     */
    public function getDefaultCollectionRecordSpecs()
    {
        $spec = new RecordDataFormatter\SpecBuilder();
        $spec->setLine('Summary', 'getSummary');
        $spec->setTemplateLine(
            'Main Authors', 'getDeduplicatedAuthors', 'data-authors.phtml',
            [
                'useCache' => true,
                'labelFunction' => function ($data) {
                    return count($data['main']) > 1
                        ? 'Main Authors' : 'Main Author';
                },
                'context' => ['type' => 'main', 'schemaLabel' => 'author'],
            ]
        );
        $spec->setTemplateLine(
            'Corporate Authors', 'getDeduplicatedAuthors', 'data-authors.phtml',
            [
                'useCache' => true,
                'labelFunction' => function ($data) {
                    return count($data['corporate']) > 1
                        ? 'Corporate Authors' : 'Corporate Author';
                },
                'context' => ['type' => 'corporate', 'schemaLabel' => 'creator'],
            ]
        );
        $spec->setTemplateLine(
            'Other Authors', 'getDeduplicatedAuthors', 'data-authors.phtml',
            [
                'useCache' => true,
                'context' => [
                    'type' => 'secondary', 'schemaLabel' => 'contributor'
                ],
            ]
        );
        $spec->setLine('Language', 'getLanguages');
        $spec->setLine(
            'Format', 'getFormats', 'RecordHelper',
            ['helperMethod' => 'getFormatList']
        );
        $spec->setLine('Access', 'getAccessRestrictions');
        $spec->setLine('Related Items', 'getRelationshipNotes');
        return $spec->getArray();
    }

    /**
     * Get default specifications for displaying data in core metadata.
     *
     * @return array
     */
    public function getDefaultCoreSpecs()
    {
        $spec = new RecordDataFormatter\SpecBuilder();
        $spec->setTemplateLine(
            'Published in', 'getContainerTitle', 'data-containerTitle.phtml'
        );
        $spec->setLine(
            'New Title', 'getNewerTitles', null, ['recordLink' => 'title']
        );
        $spec->setLine(
            'Previous Title', 'getPreviousTitles', null, ['recordLink' => 'title']
        );
        $spec->setTemplateLine(
            'Main Authors', 'getDeduplicatedAuthors', 'data-authors.phtml',
            [
                'useCache' => true,
                'labelFunction' => function ($data) {
                    return count($data['primary']) > 1
                        ? 'Main Authors' : 'Main Author';
                },
                'context' => [
                    'type' => 'primary',
                    'schemaLabel' => 'author',
                    'requiredDataFields' => [
                        ['name' => 'role', 'prefix' => 'CreatorRoles::']
                    ]
                ]
            ]
        );
        $spec->setTemplateLine(
            'Corporate Authors', 'getDeduplicatedAuthors', 'data-authors.phtml',
            [
                'useCache' => true,
                'labelFunction' => function ($data) {
                    return count($data['corporate']) > 1
                        ? 'Corporate Authors' : 'Corporate Author';
                },
                'context' => [
                    'type' => 'corporate',
                    'schemaLabel' => 'creator',
                    'requiredDataFields' => [
                        ['name' => 'role', 'prefix' => 'CreatorRoles::']
                    ]
                ]
            ]
        );
        $spec->setTemplateLine(
            'Other Authors', 'getDeduplicatedAuthors', 'data-authors.phtml',
            [
                'useCache' => true,
                'context' => [
                    'type' => 'secondary',
                    'schemaLabel' => 'contributor',
                    'requiredDataFields' => [
                        ['name' => 'role', 'prefix' => 'CreatorRoles::']
                    ]
                ],
            ]
        );
        $spec->setLine(
            'Format', 'getFormats', 'RecordHelper',
            ['helperMethod' => 'getFormatList']
        );
        $spec->setLine('Language', 'getLanguages');
        $spec->setTemplateLine(
            'Published', 'getPublicationDetails', 'data-publicationDetails.phtml'
        );
        $spec->setLine(
            'Edition', 'getEdition', null,
            ['prefix' => '<span property="bookEdition">', 'suffix' => '</span>']
        );
        $spec->setTemplateLine('Series', 'getSeries', 'data-series.phtml');
        $spec->setTemplateLine(
            'Subjects', 'getAllSubjectHeadings', 'data-allSubjectHeadings.phtml'
        );
        $spec->setTemplateLine(
            'child_records', 'getChildRecordCount', 'data-childRecords.phtml',
            ['allowZero' => false]
        );
        $spec->setTemplateLine('Online Access', true, 'data-onlineAccess.phtml');
        $spec->setTemplateLine(
            'Related Items', 'getAllRecordLinks', 'data-allRecordLinks.phtml'
        );
        $spec->setTemplateLine('Tags', true, 'data-tags.phtml');
        return $spec->getArray();
    }

    /**
     * Get default specifications for displaying data in the description tab.
     *
     * @return array
     */
    public function getDefaultDescriptionSpecs()
    {
        $spec = new RecordDataFormatter\SpecBuilder();
        $spec->setLine('Summary', 'getSummary');
        $spec->setLine('Published', 'getDateSpan');
        $spec->setLine('Item Description', 'getGeneralNotes');
        $spec->setLine('Physical Description', 'getPhysicalDescriptions');
        $spec->setLine('Publication Frequency', 'getPublicationFrequency');
        $spec->setLine('Playing Time', 'getPlayingTimes');
        $spec->setLine('Format', 'getSystemDetails');
        $spec->setLine('Audience', 'getTargetAudienceNotes');
        $spec->setLine('Awards', 'getAwards');
        $spec->setLine('Production Credits', 'getProductionCredits');
        $spec->setLine('Bibliography', 'getBibliographyNotes');
        $spec->setLine('ISBN', 'getISBNs');
        $spec->setLine('ISSN', 'getISSNs');
        $spec->setLine('DOI', 'getCleanDOI');
        $spec->setLine('Related Items', 'getRelationshipNotes');
        $spec->setLine('Access', 'getAccessRestrictions');
        $spec->setLine('Finding Aid', 'getFindingAids');
        $spec->setLine('Publication_Place', 'getHierarchicalPlaceNames');
        $spec->setTemplateLine('Author Notes', true, 'data-authorNotes.phtml');
        return $spec->getArray();
    }

    public function getLibflExemplarSpecs()
    {
        $spec = new RecordDataFormatter\SpecBuilder();
        $spec->setLine('Exemplar', 'getExemplars');
        return $spec->getArray();
    }
    
    public function getLibflDescriptionSpecs()
    {
        $spec = new RecordDataFormatter\SpecBuilder();
        $spec->setLine('Fields', 'getAllFields');
        return $spec->getArray();
    }

    public function getDefaultBjvvvSpecs()
    {
		$spec = new RecordDataFormatter\SpecBuilder();
		$spec->setLine('title_short', 'getTitleShort');
		/*// Главный автор
		$spec->setTemplateLine(
			'MainAuthors', 'getDeduplicatedAuthors', 'data-authors.phtml',
			[
				'useCache' => true,
				'labelFunction' => function($data) {
					return count($data['primary']) > 1 ? 'Main Authors' : 'Main Author';
				},
				'context' => [
					'type' => 'primary',
					'schemaLabel' => 'author',
					'requireDataFields' => [
						['name' => 'role', 'prefix' => 'CreatorRoles::']
					]
				]
			]
		);*/
                $spec->setLine('author', 'getAuthor');
                $spec->setLine('author2', 'getAuthor2');
                $spec->setLine('author_corporate', 'getAuthorCorporate');
                $spec->setLine('level', 'getLevel');
                $spec->setLine('title_another_chart', 'getTitleAnotherChart');
                $spec->setLine('title_alt', 'getTitleAlt');
                $spec->setLine('country', 'getCountry');
                $spec->setLine('placeofpublication', 'getPlacesOfPublication');
                $spec->setLine('publisher', 'getPublishers');
                $spec->setLine('publishdate', 'getPublicationDates');
                $spec->setLine('language', 'getLanguages');
                $spec->setLine('Volume', 'getVolume');
                $spec->setLine('genre', 'getGenres');
                $spec->setLine('topic', 'getTopic');
                $spec->setLine('fund', 'getFund');
                $spec->setLine('format', 'getFormat');
                $spec->setLine('MethodOfAccess', 'getMethodOfAccess');
		return $spec->getArray();
    }
    
    public function getDefaultLitresSpecs()
    {
		$spec = new RecordDataFormatter\SpecBuilder();
		$spec->setLine('title_short', 'getTitleShort');
                $spec->setLine('author', 'getAuthor');
                $spec->setLine('author2', 'getAuthor2');
                $spec->setLine('author_corporate', 'getAuthorCorporate');
                $spec->setLine('level', 'getLevel');
                $spec->setLine('title_another_chart', 'getTitleAnotherChart');
                $spec->setLine('title_alt', 'getTitleAlt');
                $spec->setLine('country', 'getCountry');
                $spec->setLine('placeofpublication', 'getPlacesOfPublication');
                $spec->setLine('publisher', 'getPublishers');
                $spec->setLine('publishdate', 'getPublicationDates');
                $spec->setLine('language', 'getLanguages');
                $spec->setLine('Volume', 'getVolume');
                $spec->setLine('genre', 'getGenres');
                $spec->setLine('topic', 'getTopic');
                $spec->setLine('fund', 'getFund');
                $spec->setLine('format', 'getFormat');
                $spec->setLine('MethodOfAccess', 'getMethodOfAccess');
		return $spec->getArray();
    }
    
    public function getDefaultPeriodSpecs()
    {
		$spec = new RecordDataFormatter\SpecBuilder();
                $spec->setLine('title_short', 'getTitleShort');
                $spec->setLine('author', 'getAuthor');
                $spec->setLine('author2', 'getAuthor2');
                $spec->setLine('author_corporate', 'getAuthorCorporate');
                $spec->setLine('level', 'getLevel');
                $spec->setLine('title_another_chart', 'getTitleAnotherChart');
                $spec->setLine('title_alt', 'getTitleAlt');
                $spec->setLine('country', 'getCountry');
                $spec->setLine('placeofpublication', 'getPlacesOfPublication');
                $spec->setLine('publisher', 'getPublishers');
                $spec->setLine('publishdate', 'getPublicationDates');
                $spec->setLine('language', 'getLanguages');
                $spec->setLine('Volume', 'getVolume');
                $spec->setLine('genre', 'getGenres');
                $spec->setLine('topic', 'getTopic');
                $spec->setLine('fund', 'getFund');
                $spec->setLine('format', 'getFormat');
                $spec->setLine('MethodOfAccess', 'getMethodOfAccess');
                // Вид издания (period_ModeOfPublication)
                $spec->setLine('period_mode_of_publication', 'period_getModeOfPublications'); 
                // Язык (period_Language)
                $spec->setLine('period_language', 'period_getLanguages');
                // Периодичность (period_Periodicity)
                $spec->setLine('period_periodicity', 'period_getPeriodicity');
                // Доступные года (period_Years)
                $spec->setLine('period_years', 'period_getYears');
                // Гиперссылки (period_HyperLink)
                $spec->setLine('period_hyperlink', 'period_getHyperLinks');
                
		return $spec->getArray();
    }
}
