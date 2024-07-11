import { useEffect, useState } from "react";
import UserModel, { IUser } from "../models/User";
import DataForm from '../components/DataForm';

export default function Dashboard() {
  const [userList, setUserList] = useState<IUser[]>([]);

  useEffect(() => {
    UserModel.getAllUsers()
      .then((result) => {
        setUserList(result.data.data);
      })
      .catch((err) => {
        console.log(err);
      });
  }, []);

  return (
    <DataForm initialDataList={userList}/>
  );
}
