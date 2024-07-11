import AppBar from "@mui/material/AppBar";
import Box from "@mui/material/Box";
import Toolbar from "@mui/material/Toolbar";
import Typography from "@mui/material/Typography";
import Button from "@mui/material/Button";
import { Link } from "react-router-dom";

const navItems = [
  {
    title: "Home",
    href: "/",
  },
  {
    title: "Dashboard",
    href: "/dashboard",
  },
];

export default function Navbar() {
  return (
    <Box sx={{ display: "flex" }}>
      <AppBar component="nav">
        <Toolbar sx={{ display: "flex", justifyContent: "space-around" }}>
          <Typography variant="h6" component="div" sx={{ display: "block" }}>
            LOGO
          </Typography>
          <Box sx={{ display: "block" }}>
            {navItems.map((item) => (
              <Link to={item.href} key={item.title}>
                <Button
                  key={item.title}
                  sx={{ color: "#fff" }}
                >
                  {item.title}
                </Button>
              </Link>
            ))}
          </Box>
        </Toolbar>
      </AppBar>
    </Box>
  );
}
