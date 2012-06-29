<?php
/**
 * Flexpage
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @copyright Copyright (c) 2009 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @package course/format/flexpage
 * @author Mark Nielsen
 */

/**
 * Flexpage backup plugin
 *
 * @author Mark Nielsen
 * @package format_flexpage
 */
class backup_format_flexpage_plugin extends backup_format_plugin {
    /**
     * Returns the format information to attach to course element
     */
    protected function define_course_plugin_structure() {

        // Define the virtual plugin element with the condition to fulfill
        $plugin = $this->get_plugin_element(null, '/course/format', 'flexpage');

        // Create one standard named plugin element (the visible container)
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // Connect the visible container ASAP
        $plugin->add_child($pluginwrapper);

        // Now create the format specific structures
        $pages = new backup_nested_element('pages');
        $page  = new backup_nested_element('page', array('id'), array(
            'name',
            'display',
            'navigation',
            'availablefrom',
            'availableuntil',
            'releasecode',
            'showavailability',
            'parentid',
            'weight',
        ));

        $regions = new backup_nested_element('regions');
        $region  = new backup_nested_element('region', array('id'), array('region', 'width'));

        $completions = new backup_nested_element('completions');
        $completion  = new backup_nested_element('completion', array('id'), array('cmid', 'requiredcompletion'));

        $grades = new backup_nested_element('grades');
        $grade  = new backup_nested_element('grade', array('id'), array('gradeitemid', 'grademin', 'grademax'));

        $menus = new backup_nested_element('menus');
        $menu  = new backup_nested_element('menu', array('id'), array('name', 'render', 'displayname', 'useastab'));

        $links = new backup_nested_element('links');
        $link  = new backup_nested_element('link', array('id'), array('type', 'weight'));

        $configs = new backup_nested_element('configs');
        $config  = new backup_nested_element('config', array('id'), array('name', 'value'));

        // Now the format specific tree
        $pluginwrapper->add_child($pages);
        $pages->add_child($page);

        $page->add_child($regions);
        $regions->add_child($region);

        $page->add_child($completions);
        $completions->add_child($completion);

        $page->add_child($grades);
        $grades->add_child($grade);

        $pluginwrapper->add_child($menus);
        $menus->add_child($menu);

        $menu->add_child($links);
        $links->add_child($link);

        $link->add_child($configs);
        $configs->add_child($config);

        // Set source to populate the data
        $page->set_source_table('format_flexpage_page', array('courseid' => backup::VAR_COURSEID));
        $region->set_source_table('format_flexpage_region', array('pageid' => backup::VAR_PARENTID));
        $completion->set_source_table('format_flexpage_completion', array('pageid' => backup::VAR_PARENTID));
        $grade->set_source_table('format_flexpage_grade', array('pageid' => backup::VAR_PARENTID));
        $menu->set_source_table('block_flexpagenav_menu', array('courseid' => backup::VAR_COURSEID));
        $link->set_source_table('block_flexpagenav_link', array('menuid' => backup::VAR_PARENTID));
        $config->set_source_table('block_flexpagenav_config', array('linkid' => backup::VAR_PARENTID));

        // Annotate ids
        $grade->annotate_ids('grade_item', 'gradeitemid');

        return $plugin;
    }
}