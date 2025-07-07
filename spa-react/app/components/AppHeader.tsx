import { Box, Typography } from "@mui/material";

const AppHeader = () => (
  <Box
    display="flex"
    alignItems="baseline"
    justifyContent="space-between"
    mb={3}
  >
    <Typography variant="h4" component="h1">
      SlowRow
    </Typography>
    <Typography variant="body1">
      An <b>AnchorLess</b> feature subset.
    </Typography>
  </Box>
);

export default AppHeader;
