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

/**
 * Access tab
 *
 * @category VuFind
 * @package RecordTabs
 * @author Maksim Kuleba <maksim.a.kuleba@libfl.ru>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 */
class Exemplar extends AbstractBase {
    /**
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Exemplars';
    }

}
