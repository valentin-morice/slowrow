import axios from "axios";
import { useCallback } from "react";
import type { DocumentFile } from "../../types/files";

const API_BASE_URL = "http://localhost:8000/api";

export const useFileApi = () => {
  const fetchFiles = useCallback(async () => {
    const response = await axios.get(`${API_BASE_URL}/files`);
    return response.data.data;
  }, []);

  const uploadFile = useCallback(async (type: string, file: File) => {
    const formData = new FormData();
    formData.append("type", type);
    formData.append("file", file);

    const response = await axios.post<{ data: DocumentFile }>(
      `${API_BASE_URL}/files`,
      formData,
      {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      }
    );

    return response.data.data;
  }, []);

  const deleteFile = useCallback(async (id: number) => {
    await axios.delete(`${API_BASE_URL}/files/${id}`);
  }, []);

  return {
    fetchFiles,
    uploadFile,
    deleteFile,
  };
};
