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
                switch ($exemplar->exemplar_access_group) {
                    case '1':
                        $access_group = '1_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '2':
                        $access_group = '2_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '3':
                        $access_group = '3_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '4':
                        $access_group = '4_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '5':
                        $access_group = '5_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '6':
                        $access_group = '8_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '7':
                        $access_group = '9_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '8':
                        $access_group = '6_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '9':
                        $access_group = '7_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '10':
                        $access_group = '10_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '11':
                        $access_group = '11_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '12':
                        $access_group = '12_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    case '13':
                        $access_group = '13_access_group_'.$exemplar->exemplar_access_group;
                        break;
                    default:
                        $access_group = '14_access_group_'.$exemplar->exemplar_access_group;
                        break;
                }
                if (in_array($exemplar->exemplar_access.'.'.$exemplar->exemplar_location, array_keys($accessExemplars[$access][$access_group]))) {
                    $accessExemplars[$access][$access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location] = array(
                        'exemplar_id' => array_merge($accessExemplars[$access][$access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location]['exemplar_id'], array($exemplar->exemplar_id)),
                        'exemplar_access_code' => $exemplar->exemplar_access,
                        'exemplar_access_group' => $exemplar->exemplar_access_group,
                        'exemplar_location' => $exemplar->exemplar_location,
                        'exemplar_rack_location' => array_merge($accessExemplars[$access][$access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location]['exemplar_rack_location'], array($exemplar->exemplar_rack_location)),
                        'exemplar_placing_cipher' => array_merge($accessExemplars[$access][$access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location]['exemplar_placing_cipher'], array($exemplar->exemplar_placing_cipher)),
                        'exemplar_inventory_number' => array_merge($accessExemplars[$access][$access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location]['exemplar_inventory_number'], array($exemplar->exemplar_inventory_number)),
                        //'exemplar_inv_note' => array_merge($accessExemplars[$exemplar->exemplar_access.'.'.$exemplar->exemplar_location]['exemplar_inv_note'], array($exemplar->exemplar_inv_note)),
                        'exemplar_hyperlink' => $exemplar->exemplar_hyperlink,
                    );
                } else {
                    $accessExemplars[$access][$access_group][$exemplar->exemplar_access.'.'.$exemplar->exemplar_location] = array(
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
        ksort($accessExemplars);
        foreach ($accessExemplars as $key => $value) {
            $access_method = preg_replace('/^\d{1,2}_/', 'access_', $key);
            $sortAccessMethod[$access_method] = $value; // Создаем новый массив, удалив информацию для сортировки ключей 'MethodOfAccess'
            ksort($sortAccessMethod[$access_method], SORT_NUMERIC); // Сортируем ключи 'GroupAccess'
            foreach ($sortAccessMethod[$access_method] as $key => $value) {
                $access_group = preg_replace('/^\d{1,2}_/', '', $key);
                $exemplarsWithAccess[$access_method][$access_group] = $value;
            }
        }
        return $exemplarsWithAccess;
    }

}
