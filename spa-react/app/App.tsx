import { useState, useEffect, useCallback } from "react";
import {
  Container,
  Grid,
  Snackbar,
  Button,
  Box,
  CircularProgress,
} from "@mui/material";
import DocumentSection from "./components/DocumentSection";
import { useFileApi } from "./hooks/useFilesApi";
import { useSnackbar } from "./hooks/useSnackbar";
import type { Files, DocumentType } from "../types/files";
import AppHeader from "./components/AppHeader";

const DOCUMENT_SECTIONS = [
  { type: "photo" as DocumentType, title: "Photo" },
  { type: "visa" as DocumentType, title: "Visa" },
  { type: "identity_document" as DocumentType, title: "Identity Document" },
];

const initialFilesState: Files = {
  photo: [],
  visa: [],
  identity_document: [],
};

function App() {
  const [files, setFiles] = useState<Files>(initialFilesState);
  const [isLoading, setIsLoading] = useState(true);
  const { fetchFiles, uploadFile, deleteFile } = useFileApi();
  const { visible, message, color, showSnackbar, hideSnackbar } = useSnackbar();

  useEffect(() => {
    const loadFiles = async () => {
      try {
        const fetchedData = (await fetchFiles()) as Files;
        setFiles({
          photo: fetchedData.photo || [],
          visa: fetchedData.visa || [],
          identity_document: fetchedData.identity_document || [],
        });
      } catch (error) {
        showSnackbar("Error fetching files.", "error");
      } finally {
        setIsLoading(false);
      }
    };
    loadFiles();
  }, [fetchFiles, showSnackbar]);

  const handleUpload = useCallback(
    async (type: DocumentType, fileToUpload: File) => {
      try {
        const apiResponse = await uploadFile(type, fileToUpload);
        setFiles((prevFiles) => ({
          ...prevFiles,
          [type]: [...(prevFiles[type] || []), apiResponse],
        }));
        showSnackbar("File uploaded successfully!", "success");
      } catch (error) {
        showSnackbar("Error uploading file.", "error");
      }
    },
    [uploadFile, showSnackbar]
  );

  const handleDelete = useCallback(
    async (id: number, type: DocumentType) => {
      try {
        await deleteFile(id);
        setFiles((prevFiles) => ({
          ...prevFiles,
          [type]: prevFiles[type]?.filter((file) => file.id !== id) || [],
        }));
        showSnackbar("File deleted successfully!", "success");
      } catch (error) {
        showSnackbar("Error deleting file.", "error");
      }
    },
    [deleteFile, showSnackbar]
  );

  return (
    <Container sx={{ padding: 4 }}>
      <AppHeader />
      {isLoading ? (
        <Box
          display="flex"
          justifyContent="center"
          alignItems="center"
          sx={{ height: "50vh" }}
        >
          <CircularProgress />
        </Box>
      ) : (
        <Grid container spacing={3}>
          {DOCUMENT_SECTIONS.map((section) => (
            <Grid size="grow" key={section.title}>
              <DocumentSection
                title={section.title}
                documentType={section.type}
                documents={files[section.type]}
                onUpload={handleUpload}
                onDelete={handleDelete}
              />
            </Grid>
          ))}
        </Grid>
      )}
      <Snackbar
        open={visible}
        autoHideDuration={3000}
        onClose={hideSnackbar}
        message={message}
        action={
          <Button color="inherit" size="small" onClick={hideSnackbar}>
            Close
          </Button>
        }
        slotProps={{
          content: {
            sx: { backgroundColor: color === "error" ? "red" : "green" },
          },
        }}
      />
    </Container>
  );
}

export default App;
