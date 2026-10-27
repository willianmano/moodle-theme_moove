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
 * Event list item for the Timeline block.
 *
 * Matches the DOM structure of the legacy event-list-item.mustache template.
 *
 * @module     block_timeline/views/EventListItem
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {useState, useEffect} from 'react';
import {getString} from '@moodle/lms/core/stringUtils';
import {Badge} from '@moodlehq/design-system';
import {ActivityIcon} from '@moodle/lms/block_timeline/views/ActivityIcon';
import type {CalendarEvent} from '@moodle/lms/block_timeline/common/types';

interface EventListItemProps {
    event: CalendarEvent;
    courseview?: boolean;
}

/**
 * Renders a single timeline event item, matching event-list-item.mustache.
 *
 * In courseview mode (used inside CoursesView) the course name is omitted from
 * the subtitle.
 */
export default function EventListItem({event, courseview = false}: EventListItemProps) {
    const [overdueLabel, setOverdueLabel] = useState('');
    const [dueSoonLabel, setDueSoonLabel] = useState('');
    const [ariaLabel, setAriaLabel] = useState('');

    useEffect(() => {
        getString('overdue', 'block_timeline').then(setOverdueLabel);
    }, []);

    // Due-proximity badge: "Overdue" (red), "Due <weekday>" within the next week (amber),
    // or the plain due date further out — matching the day/weekday/date hierarchy used
    // elsewhere in the dashboard (e.g. the upcoming events block).
    const dueDate = new Date(event.timesort * 1000);
    const startOfToday = new Date();
    startOfToday.setHours(0, 0, 0, 0);
    const startOfDueDay = new Date(dueDate);
    startOfDueDay.setHours(0, 0, 0, 0);
    const daysUntilDue = Math.round((startOfDueDay.getTime() - startOfToday.getTime()) / 86400000);
    const dueSoon = !event.overdue && daysUntilDue >= 0 && daysUntilDue <= 6;
    const weekdayShort = dueDate.toLocaleDateString(undefined, {weekday: 'short'});
    // Built as "<day> <month>" explicitly (rather than relying on the locale's own day/month
    // ordering) to match the day-month badge format used elsewhere on the dashboard.
    const dateShort = `${dueDate.getDate()} ${dueDate.toLocaleDateString(undefined, {month: 'short'})}`;

    useEffect(() => {
        if (!dueSoon) {
            return;
        }
        getString('duebadge', 'theme_moove', weekdayShort).then(setDueSoonLabel);
    }, [dueSoon, weekdayShort]);

    useEffect(() => {
        getString('ariaeventlistitem', 'block_timeline', {
            name:   event.activityname ?? '',
            course: event.course.fullnamedisplay,
            date:   event.formatteddatetime,
        }).then(setAriaLabel);
    }, [event.activityname, event.course.fullnamedisplay, event.formatteddatetime]);

    const time = new Date(event.timesort * 1000).toLocaleTimeString(undefined, {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    });

    const purposeClass = event.purpose ? ` ${event.purpose}` : '';
    const iconContainerClass =
        `small courseicon align-self-center mx-2 mb-1 mb-sm-0 text-nowrap activityiconcontainer${purposeClass}`;

    return (
        <div
            className="list-group-item timeline-event-list-item flex-column pt-2 pb-0 border-0 px-0"
            data-region="event-list-item"
        >
            <div className="d-flex flex-wrap pb-1">
                <div className="d-flex me-auto pb-1 mw-100 timeline-name">
                    {event.icon && (
                        <div className={iconContainerClass}>
                            <ActivityIcon modulename={event.modulename} iconurl={event.icon.iconurl} alt={event.icon.alttext} />
                        </div>
                    )}

                    <div className="event-name-container flex-grow-1 line-height-4 nowrap text-truncate">
                        <div className="d-flex">
                            <h5 className="h6 event-name mb-0 pb-1 text-truncate">
                                <a href={event.url} title={event.name} aria-label={ariaLabel || undefined}>
                                    {event.activityname}
                                </a>
                            </h5>
                        </div>
                        <small className="mb-0">
                            {!courseview && event.course?.fullnamedisplay && (
                                <>{event.course.fullnamedisplay}</>
                            )}
                            {event.overdue && (
                                <Badge variant="danger" pill label={overdueLabel} className="ms-1" />
                            )}
                            {dueSoon && dueSoonLabel && (
                                <Badge variant="info" pill subtle label={dueSoonLabel} className="ms-1" />
                            )}
                            {!event.overdue && !dueSoon && (
                                <Badge variant="primary" pill subtle label={dateShort} className="ms-1" />
                            )}
                        </small>
                    </div>
                </div>

                {event.action?.actionable && (
                    <div className="d-flex timeline-action-button">
                        <h5 className="h6 event-action">
                            <a
                                className="btn btn-primary btn-sm text-nowrap text-white"
                                href={event.action.url}
                                aria-label={event.action.name}
                                title={event.action.name}
                            >
                                {event.action.name}
                                {event.action.showitemcount && (
                                    <Badge variant="secondary" label={String(event.action.itemcount)} />
                                )}
                            </a>
                        </h5>
                    </div>
                )}
            </div>
        </div>
    );
}
