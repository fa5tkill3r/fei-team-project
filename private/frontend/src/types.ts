export interface User {
  id: number
  email: string
  first_name: string
  last_name: string
  created_at: string
  updated_at: string
  avatar?: string
  role: Role
}

export interface Team {
  id: number
  name: string
  description: string
  created_at: string
  updated_at: string
  users: User[]
}

export interface Task {
  id: number
  name: string
  description: string
  users: User[]
  tags: Tag[]
  severity: string
  created_at: string
  deadline?: string
  created_by: User
  parent?: Task
  children: Task[]
}

export interface TaskRequest {
  name: string
  description: string
  users: number[]
  tags: number[]
  severity: string
  deadline?: string
  parent?: number
}

export interface Tag {
  id: number
  name: string
  color: string
}

export interface Role {
  id: number
  name: string
  slug: string
  task_access: boolean
  task_create: boolean
  task_delete: boolean
  user_access: boolean
  user_add: boolean
  user_remove: boolean
  role_access: boolean
  role_add: boolean
  role_delete: boolean
  team_info: boolean
  created_at: string
  updated_at: string
}
