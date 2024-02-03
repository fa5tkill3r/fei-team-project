export interface Task {
  id: number
  name: string
  description: string
  users: number[]
  tags: number[]
  severity: string
  created_at: string
  deadline?: string
}

export type TaskRequest = Omit<Task, 'id' | 'created_at'>
