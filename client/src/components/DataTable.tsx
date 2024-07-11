import Table from "@mui/material/Table";
import TableBody from "@mui/material/TableBody";
import TableCell from "@mui/material/TableCell";
import TableContainer from "@mui/material/TableContainer";
import TableHead from "@mui/material/TableHead";
import TableRow from "@mui/material/TableRow";
import Paper from "@mui/material/Paper";
import PostModel, { IPost } from "../models/Post";
import { IconButton } from "@mui/material";
import { Delete } from "@mui/icons-material";
import { useEffect, useState } from "react";

interface DataTableProps {
  initialDataList: IPost[];
}

export default function DataTable({ initialDataList }: DataTableProps) {
  const [dataList, setDataList] = useState<IPost[]>([]);

  useEffect(() => {
    setDataList(initialDataList);
  }, [initialDataList]);

  
  const deleteData = (id: number) => {
    PostModel.deletePostById(id)
      .then(() => {
        setDataList(dataList.filter((data) => data.id !== id));
      })
      .catch((err) => {
        console.log(err);
      });
  };

  return (
    <TableContainer component={Paper}>
      <Table sx={{ minWidth: 650 }} aria-label="simple table">
        <TableHead>
          <TableRow>
            <TableCell>#</TableCell>
            <TableCell align="left">Username</TableCell>
            <TableCell align="left">Title</TableCell>
            <TableCell align="left">Body</TableCell>
            <TableCell align="left"></TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {dataList.map((data: IPost) => (
            <TableRow
              key={data.id}
              sx={{ "&:last-child td, &:last-child th": { border: 0 } }}
            >
              <TableCell component="th" scope="row">
                {data.id}
              </TableCell>
              <TableCell align="left">{data.user.username}</TableCell>
              <TableCell align="left">{data.title}</TableCell>
              <TableCell align="left">{data.body}</TableCell>
              <TableCell align="left">
                <IconButton
                  aria-label="delete"
                  onClick={() => deleteData(data.id)}
                >
                  <Delete />
                </IconButton>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );
}
