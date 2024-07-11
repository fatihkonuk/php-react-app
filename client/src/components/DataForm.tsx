import Button from "@mui/material/Button";
import CssBaseline from "@mui/material/CssBaseline";
import TextField from "@mui/material/TextField";
import Box from "@mui/material/Box";
import Typography from "@mui/material/Typography";
import Container from "@mui/material/Container";
import {
  FormControl,
  InputLabel,
  MenuItem,
  Select,
  SelectChangeEvent,
} from "@mui/material";
import { useEffect, useState } from "react";
import { IUser } from "../models/User";
import PostModel from "../models/Post";
import { useNavigate } from "react-router-dom";

interface DataTableProps {
  initialDataList: IUser[];
}

export default function Dashboard({ initialDataList }: DataTableProps) {
  const [dataList, setDataList] = useState<IUser[]>([]);
  const [selectedUser, setSelectedUser] = useState<number | string>("");
  const navigate = useNavigate();

  useEffect(() => {
    setDataList(initialDataList);
  }, [initialDataList]);

  const handleChange = (event: SelectChangeEvent<string | number>) => {
    setSelectedUser(event.target.value as string | number);
  };

  const handleSubmit = (event: any) => {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    PostModel.createPost({
      userId: selectedUser,
      title: data.get("title"),
      body: data.get("body"),
    })
      .then(() => {
        navigate("/");
      })
      .catch((err) => {
        console.log(err);
      });
  };

  return (
    <Container component="main" maxWidth="xs">
      <CssBaseline />
      <Box
        sx={{
          marginTop: 8,
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
        }}
      >
        <Typography component="h1" variant="h5">
          Create Post
        </Typography>
        <Box component="form" onSubmit={handleSubmit} noValidate sx={{ mt: 1 }}>
          <FormControl fullWidth>
            <InputLabel id="demo-simple-select-label">Username</InputLabel>
            <Select
              labelId="demo-simple-select-label"
              id="demo-simple-select"
              value={selectedUser}
              onChange={handleChange}
              name="userid"
            >
              {dataList.map((item) => (
                <MenuItem value={item.id} key={item.username}>
                  {item.username}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
          <TextField
            margin="normal"
            required
            fullWidth
            id="Title"
            label="Title"
            name="title"
          />
          <TextField
            margin="normal"
            required
            fullWidth
            name="body"
            label="Body"
            id="body"
          />
          <Button
            type="submit"
            fullWidth
            variant="contained"
            sx={{ mt: 3, mb: 2 }}
          >
            Create
          </Button>
        </Box>
      </Box>
    </Container>
  );
}
