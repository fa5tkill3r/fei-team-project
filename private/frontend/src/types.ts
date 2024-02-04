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
  severity: string
  created_at: string
  deadline?: string
}

export interface TaskRequest {
  name: string
  description: string
  users: number[]
  tags: number[]
  severity: string
  deadline?: string
}

export interface Tag {
  id: number
  name: string
  color: string
}
