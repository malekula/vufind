<?php
/**
 * Access tab
 *
 * @category VuFind
 * @package RecordTabs
 * @author Maksim Kuleba <maksim.a.kuleba@libfl.ru>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 */
namespace VuFind\RecordTab;
use VuFind\View\Helper\Root\RecordDataFormatter;
use Zend\View\Renderer\PhpRenderer;
/**
 * Access tab
 *
 * @category VuFind
 * @package RecordTabs
 * @author Maksim Kuleba <maksim.a.kuleba@libfl.ru>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 */
class LIBFLAccess extends AbstractBase {
    /**
     *
     * @return string
     */
    public function getDescription()
    {
        return 'tabAccess';
    }

    public function sortExemplars($accessMethods = array(), $exemplars = array()) {
        $sortExemplars = array();
        foreach ($accessMethods as $accessMethodCode) {
            switch ($accessMethodCode) {
                case '4000':
                    $access = '4_libraryAccess';
                    break;
                case '4001':
                    $access = '3_homeAccess';
                    break;
                case '4002':
                    $access = '1_remoteAccess';
                    break;
                case '4003':
                    $access = '2_printAccess';
                    break;
                case '4004':
                    $access = '5_clarifyAccess';
                    break;
                default:
                    $access = '99_unknownAccess';
                    break;
            }

            foreach ($exemplars as $num => $exemplar) {
                if (in_array($exemplar->exemplar_access.'.'.$exemplar->exemplar_location, array_keys($arr[$access][$exemplar->exemplar_access_group]))) {
                    $arr[$access][$exemplar->exemplar_access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location] = array(
                        'exemplar_id' => array_merge($arr[$access][$exemplar->exemplar_access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location]['exemplar_id'], array($exemplar->exemplar_id)),
                        'exemplar_access_code' => $exemplar->exemplar_access,
                        'exemplar_access_group' => $exemplar->exemplar_access_group,
                        'exemplar_location' => $exemplar->exemplar_location,
                        'exemplar_rack_location' => array_merge($arr[$access][$exemplar->exemplar_access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location]['exemplar_rack_location'], array($exemplar->exemplar_rack_location)),
                        'exemplar_placing_cipher' => array_merge($arr[$access][$exemplar->exemplar_access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location]['exemplar_placing_cipher'], array($exemplar->exemplar_placing_cipher)),
                        'exemplar_inventory_number' => array_merge($arr[$access][$exemplar->exemplar_access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location]['exemplar_inventory_number'], array($exemplar->exemplar_inventory_number)),
                        //'exemplar_inv_note' => array_merge($arr[$exemplar->exemplar_access.'.'.$exemplar->exemplar_location]['exemplar_inv_note'], array($exemplar->exemplar_inv_note)),
                        'exemplar_hyperlink' => $exemplar->exemplar_hyperlink,
                    );
                } else {
                    $arr[$access][$exemplar->exemplar_access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location] = array(
                        'exemplar_id' => [$num=>$exemplar->exemplar_id],
                        'exemplar_access_code' => $exemplar->exemplar_access,
                        'exemplar_access_group' => $exemplar->exemplar_access_group,
                        'exemplar_location' => $exemplar->exemplar_location,
                        'exemplar_rack_location' => [$exemplar->exemplar_rack_location],
                        'exemplar_placing_cipher' => [$exemplar->exemplar_placing_cipher],
                        'exemplar_inventory_number' => [$exemplar->exemplar_inventory_number],
                        //'exemplar_inv_note' => $exemplar->exemplar_inv_note,
                        'exemplar_hyperlink' => $exemplar->exemplar_hyperlink,
                    );
                }
            }
        }
        ksort($arr);
        foreach ($arr as $key => $value) {
            $new_key = preg_replace('/.*_/','access_',$key);
            $cleanExemplars[$new_key] = $value; // Создаем новый массив, удалив информацию для сортировки ключей 'MethodOfAccess'
            ksort($cleanExemplars[$new_key]); // Сортируем ключи 'GroupAccess'
        }
        return $cleanExemplars;
    }

}
