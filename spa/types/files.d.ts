export interface DocumentFile {
  id: number
  name: string
  created_at: string
  url: string
}

export interface Files {
  photo?: DocumentFile[]
  visa?: DocumentFile[]
  identity_document?: DocumentFile[]
}

export interface Models {
  photo?: File | null
  visa?: File | null
  identity_document?: File | null
}

export interface SnackbarState {
  visible: boolean
  message: string
  color: string
}
