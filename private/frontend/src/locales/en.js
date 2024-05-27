export default {
  yes: 'Yes',
  no: 'No',
  cancel: 'Cancel',
  remove: 'Remove',
  edit: 'Edit',
  delete: 'Delete',
  add: 'Add',
  dashboard: 'Dashboard',
  filters: 'Filters',
  confirmation: 'Confirmation',
  save: 'Save',
  create: 'Create',
  submit: 'Submit',
  auth: {
    login: 'Login',
    password: 'Password',
    remember: 'Remember me',
  },
  nav: {
    tasks: 'Tasks',
    incidents: 'Incidents',
    search: 'Search here...',
    profile: 'Profile',
    settings: 'Settings',
    logout: 'Logout',
    theses: 'Theses',
    library: 'Library',
    wiki: 'Wiki',
    admin_panel: 'Admin panel',
    no_team_selected: 'No team selected',
  },
  main: {
    tasks: 'Tasks',
    board: 'Board view',
    list: 'List view',
    incidents: 'Incidents',
  },
  task: {
    status: {
      new: 'New',
      in_progress: 'In progress',
      closed: 'Closed',
    },
    header_status: 'Status',
    header_comments: 'Comments',
    title: 'Title',
    title_placeholder: 'Add title here...',
    description: 'Description',
    description_placeholder: 'Add description here...',
    assignees: 'Assignees',
    select_assignees: 'Select assignees',
    tags: 'Tags',
    all_users: 'Asign all team users',
    no_tags: 'No tags',
    create: 'Create',
    edit: 'Edit',
    save: 'Save',
    close: 'Close',
    reopen: 'Reopen',
    no_assignees: 'No assignees',
    severity: 'Severity',
    comments: '{count} comments | {count} comment | {count} comments',
    deadline: 'Deadline',
    no_deadline: 'No deadline',
    created_at: 'created {distance}',
    select_tags: 'Select tags',
    no_results: 'No results',
    commented_at: 'commented {distance}',
    parent: 'Parent',
    no_parent: 'No parent',
    subTasks: 'Subtasks',
    select_parent: 'Select parent',
    no_description: 'No description provided',
    manage_tags: 'Manage tags',
    incident: 'Incident',
    filter_docs: `Available filters:<br>
                  <ul>
                    <li><b>is</b> - filter by status (e.g. is:open, is:closed)<br></li>
                    <li><b>assignee</b> - filter by assignee (e.g. assignee:@me, assignee:name)<br></li>
                    <li><b>tag</b> - filter by tag (e.g. tag:bug)<br></li>
                  </ul>`,
    filter: {
      open: 'Open tasks',
      closed: 'Closed tasks',
      your: 'Your tasks',
    },
    no_tasks: 'No tasks',
    filter_help: 'View filter syntax',
    delete_confirmation: {
      title: 'Delete task?',
      content: 'Are you sure you want to delete this task?',
    },
    comment: {
      add: 'Add comment',
      placeholder: "What's on your mind?",
      update: 'Update comment',
      edit: 'Edit',
      delete: 'Delete',
      in_report: 'In report',
      not_in_report: 'Not in report',
    },
    editing: 'Editing task {task}',
    new: 'New task',
  },
  settings: {
    title: 'Settings',
    locale: 'Locale',
  },
  tags: {
    title: 'Tags',
    search: 'Search tags...',
    count: '{count} tags | {count} tag | {count} tags',
    name: 'Name',
    color: 'Color',
    preview: 'Tag preview',
    add_title: 'Add tag',
    edit_title: 'Edit tag',
    delete_title: 'Delete tag',
    delete_confirm: 'Are you sure you want to delete the {0} tag?',
  },
  user_search: {
    title: 'Add people',
    search_placeholder: 'Search by full name...',
    selected_add: 'Add selected users',
  },
  admin_panel: {
    users: 'Users',
    remove_user: 'Remove user',
    remove_user_description:
      'Are you sure you want to remove the user {0} from the team?',
    member: 'Member',
    role: 'Role',
    actions: 'Actions',
    name: 'Name',
    id: 'ID',
    edit: 'Edit',
    delete: 'Delete',
    manage_incident_categories: 'Manage incident categories',
    manage_incident_types: 'Manage incident types',
    incident_categories: 'Incident categories',
    incident_types: 'Incident types',
    delete_category: "Are you sure you want to delete the category '{0}'?",
    create_category: 'Create category',
    roles: {
      add: 'Add role',
      name: 'Role name',
      roles: 'Roles',
      assigned_permissions: 'Assigned permissions',
      permissions: {
        task_access: 'Task access',
        task_create: 'Task creation',
        task_delete: 'Task deletion',
        user_access: 'User access',
        user_add: 'User creation',
        user_remove: 'User deletion',
        team_info: 'Team info',
      }
    }
  },
  incidents: {
    filter: {
      new: 'New incidents',
      in_progress: 'In progress incidents',
      closed: 'Closed incidents',
    },
    roles: {
      roles: 'Roles',
      add: 'Add role',
      edit: 'Edit role',
      name: 'Role name: ',
      assigned_permissions: 'Assigned permissions',
      permissions: {
        task_access: 'Task access',
        task_create: 'Task creation',
        task_delete: 'Task edition',
        user_access: 'User access',
        user_add: 'User creation',
        user_remove: 'User deletion',
        team_info: 'Team info',
      },
    },
    task: 'Task',
    reporter: 'Reporter',
    reported_at: 'reported {distance}',
    title: 'Title',
    title_placeholder: 'Add title here...',
    deadline: 'Deadline',
    description: 'Description',
    description_placeholder: 'Add description here...',
    reported: 'Reported',
    no_incidents: 'No incidents',
    create_task: 'Create task',
    recorded_at: 'record added {distance}',
    reports: 'Security Incident Records',
    add_report: 'Add report',
    report_date: 'Date of the report',
    solutions: 'Solutions',
    add_solution: 'Add solution',
    solution_description: 'Solution',
    solution_description_placeholder: 'Add solution description here...',
    solution_deadline: 'Solution deadline',
    responsible_person: 'Responsible person',
    responsible_person_placeholder: 'Name of responsible person',
    approve: 'Approve incident',
  },
}
