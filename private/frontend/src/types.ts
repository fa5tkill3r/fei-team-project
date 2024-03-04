export interface User {
  id: number
  email: string
  first_name: string
  last_name: string
  created_at: string
  updated_at: string
  avatar?: string
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
  status: string
  severity: string
  created_at: string
  deadline?: string
  created_by: User
  parent?: Task
  children: Task[]
  all_users: boolean
}

export interface TaskRequest {
  name: string
  description: string
  users: number[]
  tags: number[]
  severity: string
  is_closed?: boolean
  deadline?: string
  parent?: number
}

export interface Tag {
  id: number
  name: string
  color: string
}
