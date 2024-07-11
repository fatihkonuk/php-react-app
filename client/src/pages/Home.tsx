import { useState, useEffect } from "react";
import PostModel, { type IPost } from "../models/Post";
import DataTable from "../components/DataTable";

const Home = () => {
  const [postList, setPostList] = useState<IPost[]>([]);

  useEffect(() => {
    PostModel.getAllPosts()
      .then((result) => {
        setPostList(result.data.data);
      })
      .catch((err) => {
        console.log(err);
      });
  }, []);

  return (
    <div>
      <h1>Post List</h1>
      <DataTable initialDataList={postList} />
    </div>
  );
};

export default Home;
