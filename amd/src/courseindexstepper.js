// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Flags the completed/current activity state used to style each section's activity list
 * as a numbered stepper (see scss/medilearn/_courseindex.scss).
 *
 * The activity numbering and the connector line are pure CSS (a counter reset on each
 * section's own activity list, so it naturally restarts for every section). This module
 * only adds the two state classes CSS can't derive on its own:
 *  - stepper-complete, on every activity whose own completion info is complete.
 *  - stepper-current, on the first activity in a section that has trackable completion
 *    info and is not complete. Activities without completion tracking are left plain
 *    (just their number) and never become the current step, so an untracked activity at
 *    the start of a section doesn't block the stepper from ever advancing.
 *
 * core_courseformat/local/courseindex/cm replaces the whole completion node
 * (Templates.replaceNode) whenever completion changes, so a MutationObserver on the
 * course index subtree is used to stay in sync instead of listening for attribute
 * changes on individual nodes.
 *
 * @module     theme_moove/courseindexstepper
 * @copyright  2026 Willian Mano {@link https://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

const SELECTORS = {
    SECTION_CMLIST: '.courseindex-sectioncontent',
    CM: ':scope > .courseindex-item[data-for="cm"]',
    COMPLETION: '.completioninfo[data-for="cm_completion"]',
};

const TRACKED_COMPLETION_CLASSES = ['completion_complete', 'completion_incomplete', 'completion_fail'];

const CLASSES = {
    COMPLETE: 'stepper-complete',
    CURRENT: 'stepper-current',
};

/**
 * Read an activity item's own completion state.
 *
 * @param {Element} cm the activity <li> element
 * @return {'complete'|'incomplete'|null} null when the activity has no completion tracking
 */
const getCmCompletionState = (cm) => {
    const completion = cm.querySelector(SELECTORS.COMPLETION);
    if (!completion || !TRACKED_COMPLETION_CLASSES.some(trackedClass => completion.classList.contains(trackedClass))) {
        return null;
    }

    return completion.classList.contains('completion_complete') ? 'complete' : 'incomplete';
};

/**
 * Recompute the complete/current classes for one section's activity list.
 *
 * @param {Element} cmList the section's activity <ul> element
 */
const refreshSection = (cmList) => {
    const cms = Array.from(cmList.querySelectorAll(SELECTORS.CM));

    let currentAssigned = false;
    cms.forEach((cm) => {
        const state = getCmCompletionState(cm);
        cm.classList.toggle(CLASSES.COMPLETE, state === 'complete');

        const isCurrent = state === 'incomplete' && !currentAssigned;
        cm.classList.toggle(CLASSES.CURRENT, isCurrent);
        if (isCurrent) {
            currentAssigned = true;
        }
    });
};

/**
 * Recompute the complete/current classes for every section's activity list.
 *
 * @param {Element} root the course index root element
 */
const refresh = (root) => {
    root.querySelectorAll(SELECTORS.SECTION_CMLIST).forEach(refreshSection);
};

/**
 * Initialise the stepper state for a course index root element.
 *
 * @param {string} target CSS selector for the course index root element
 */
export const init = (target) => {
    const root = document.querySelector(target);
    if (!root) {
        return;
    }

    let scheduled = false;
    const scheduleRefresh = () => {
        if (scheduled) {
            return;
        }
        scheduled = true;
        requestAnimationFrame(() => {
            scheduled = false;
            refresh(root);
        });
    };

    scheduleRefresh();

    const observer = new MutationObserver(scheduleRefresh);
    observer.observe(root, {childList: true, subtree: true});
};

export default {init};
