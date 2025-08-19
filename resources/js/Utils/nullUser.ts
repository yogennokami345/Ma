const isNullUser = (user: User): boolean => {
    if (user === null) {
        return true;
    }
    return false;
}

export default isNullUser;
