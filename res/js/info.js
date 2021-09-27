
// React components related to admin info
const INFO = (function() {
    const e = React.createElement;

    let enrollment = false;
    function setEnrollment(enr) { enrollment = enr; }

    function Users(props) {
        if (props.users) {
            return e('span', {'class' : 'users', 'onClick' : props.showUsers},
                     e('i', {class : 'far fa-user'}), " ", props.users);
        } else {
            return "";
        }
    }

    function Views(props) {
        if (props.views) {
            return e('span', {'class' : 'views'},
                     e('i', {class : 'fas fa-eye'}), " ", props.views);
        } else {
            return "";
        }
    }

    function Time(props) {
        if (props.time) {
            return e('span', {'class' : 'time'},
                     e('i', {class : 'far fa-clock'}), " ", props.time);
        } else {
            return "";
        }
    }

    function Info(props) {
        if (props.users) {
            return e('div', props, Users(props), " ", Views(props), " ",
                     Time(props));
        } else {
            return "";
        }
    }

    function byFirst(a, b) { return a.firstname.localeCompare(b.firstname); }

    function byLast(a, b) { return a.lastname.localeCompare(b.lastname); }

    function byPdf(a, b) { return a.pdf - b.pdf; }

    function byHours(a, b) { return a.hours - b.hours; }

    function reverse(func, a, b) { return -func(a, b); }

    class ViewersHeader extends React.Component {
        constructor(props) {
            super(props);
            this.state = {
                sorted : "byHours",
                desc : false,
            };
        }

        click(name, func, orderFun) {
            func(orderFun);
            let order = "desc";
            if (this.state.sorted === name) {
                if (this.state.desc) {
                    order = "asc"
                } else {
                    order = "desc";
                }
            }
            this.setState({
                sorted : name,
                desc : order == "desc",
            });
        }

        render() {
            const headers = [];

            let firstClick =
                this.click.bind(this, 'byFirst', this.props.sort, byFirst);
            let lastClick =
                this.click.bind(this, 'byLast', this.props.sort, byLast);
            let pdfClick =
                this.click.bind(this, 'byPdf', this.props.sort, byPdf);
            let hoursClick =
                this.click.bind(this, "byHours", this.props.sort, byHours);

            let firstSort = "fas fa-sort";
            let lastSort = "fas fa-sort";
            let pdfSort = "fas fa-sort";
            let hourSort = "fas fa-sort";

            if (this.state.sorted == "byFirst") {
                if (this.state.desc) {
                    firstSort = "fas fa-sort-up";
                    firstClick =
                        this.click.bind(this, 'byFirst', this.props.sort,
                                        reverse.bind(null, byFirst));
                } else {
                    firstSort = "fas fa-sort-down";
                }
            } else if (this.state.sorted == "byLast") {
                if (this.state.desc) {
                    lastSort = "fas fa-sort-up";
                    lastClick = this.click.bind(this, 'byLast', this.props.sort,
                                                reverse.bind(null, byLast));
                } else {
                    lastSort = "fas fa-sort-down";
                }
            } else if (this.state.sorted == "byPdf") {
                if (this.state.desc) {
                    pdfSort = "fas fa-sort-up";
                    pdfClick = this.click.bind(this, 'byPdf', this.props.sort,
                                               reverse.bind(null, byPdf));
                } else {
                    pdfSort = "fas fa-sort-down";
                }
            } else if (this.state.sorted == "byHours") {
                if (this.state.desc) {
                    hourSort = "fas fa-sort-up";
                    hoursClick =
                        this.click.bind(this, "byHours", this.props.sort,
                                        reverse.bind(null, byHours));
                } else {
                    hourSort = "fas fa-sort-down";
                }
            }

            const firstIcon = e('i', {class : firstSort});
            const lastIcon = e('i', {class : lastSort});
            const pdfIcon = e('i', {class : pdfSort});
            const hourIcon = e('i', {class : hourSort});

            headers.push(e('th', {key : "th_first", onClick : firstClick},
                           'Given Names', firstIcon));
            headers.push(e('th', {key : "th_last", onClick : lastClick},
                           'Family Names', lastIcon));
            headers.push(e('th', {key : "th_pdf", onClick : pdfClick},
                           'PDF Views', pdfIcon));
            headers.push(e('th', {key : "th_hours", onClick : hoursClick},
                           'Hours', hourIcon));
            return e('tr', null, headers);
        }
    }

    function ViewersRow(props) {
        const cols = [];
        cols.push(e('td', {key : `${props.id}-first`}, props.firstname));
        cols.push(e('td', {key : `${props.id}-last`}, props.lastname));
        cols.push(e('td', {key : `${props.id}-pdf`}, props.pdf));
        cols.push(
            e('td', {key : `${props.id}-hours`, class : "hours"}, props.hours));
        return e('tr', {key : `${props.id}-row`}, cols);
    }

    class ViewersTable extends React.Component {
        constructor(props) {
            super(props);
            this.state = {users : props.users};
        }

        sort(order) {
            const sorted = this.state.users.sort(order);
            this.setState({users : sorted});
        }

        newRows(users) { this.setState({users : users}); }

        render() {
            const caption = e('caption', null, this.props.title);
            const rows = [
                e(ViewersHeader,
                  {sort : this.sort.bind(this), key : "header-row"}),
            ];
            for (const user of this.state.users) {
                rows.push(ViewersRow(user));
            }
            const tbody = e('tbody', null, rows);
            return e('table', null, caption, tbody);
        }
    }

    class Header extends React.Component {
        constructor(props) {
            super(props);
            this.state = {title : props.title};
        }
        setTitle(title) { this.setState({title : title}); }
        render() { return e('h2', null, this.state.title); }
    }

    function showTables(title, users) {
        const tables = document.getElementById("tables");
        const overlay = document.getElementById("overlay");
        ReactDOM.unmountComponentAtNode(tables);

        const enrolled = [];
        const enrol_nv = [];
        const non_enrol = [];
        for (const user of users) {
            if (enrollment[user.id]) {
                enrolled.push(user);
                enrollment[user.id].seen = true;
            } else {
                non_enrol.push(user);
            }
        }
        for (const id in enrollment) {
            const user = enrollment[id];
            if (!user.seen) {
                enrol_nv.push(user);
            }
        }

        const header = React.createElement('h2', null, title);
        const enrolTable = React.createElement(ViewersTable, {
            title : "Enrolled Users",
            users : enrolled,
        },
                                               null);
        const enrnvTable = React.createElement(ViewersTable, {
            title : "Enrolled No View",
            users : enrol_nv,
        },
                                               null);
        const non_enrTable = React.createElement(ViewersTable, {
            title : "Non-Enrolled Users",
            users : non_enrol,
        },
                                                 null);
        const combined = React.createElement('div', null, header, enrolTable,
                                             enrnvTable, non_enrTable);

        ReactDOM.render(combined, tables);
        overlay.classList.add("visible");
    }

    function hideTables() {
        document.getElementById("overlay").classList.remove("visible");
    }

    function offeringViewers() {
        const offering_id = document.getElementById('offering').dataset.id;
        const course = document.getElementById("course_num").textContent;
        const offering = document.getElementById("offering").textContent;
        const title = `${course} ${offering}`;
        fetch(`viewers?offering_id=${offering_id}`)
            .then(response => response.json())
            .then(json => showTables(title, json));
    }

    function dayViewers(evt) {
        let elm = evt.target.parentNode;
        while (!elm.dataset.day) {
            elm = elm.parentNode;
        }
        const day = elm.dataset.day;
        const day_id = elm.dataset.day_id;
        const text = elm.dataset.text;
        const title = `${day} ${text}`;
        fetch(`${day}/viewers?day_id=${day_id}`)
            .then(response => response.json())
            .then(json => showTables(title, json));
    }

    function videoViewers(evt) {
        const day_id = document.getElementById('day').dataset.id;
        let elm = evt.target.parentNode;
        while (!elm.dataset.show) {
            elm = elm.parentNode;
        }
        const video = elm.dataset.show;
        const title = video.substring(3);
        const num = video.substring(0, 2);
        fetch(`${num}/viewers?day_id=${day_id}&video=${
                  encodeURIComponent(video)}`)
            .then(response => response.json())
            .then(json => showTables(title, json));
    }

    return {
        setEnrollment,
        Info,
        offeringViewers,
        dayViewers,
        videoViewers,
        hideTables,
    };
})();
