// apiService.ts
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api'

export const useFileApi = () => {
  const fetchFiles = async () => {
    const response = await axios.get(`${API_BASE_URL}/files`)
    return response.data.data
  }

  const uploadFile = async (type: string, file: File) => {
    const formData = new FormData()
    formData.append('type', type)
    formData.append('file', file)

    const response = await axios.post(`${API_BASE_URL}/files`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

    return response.data.data
  }

  const deleteFile = async (id: number) => {
    await axios.delete(`${API_BASE_URL}/files/${id}`)
  }

  return {
    fetchFiles,
    uploadFile,
    deleteFile,
  }
}
