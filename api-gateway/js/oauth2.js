function introspectAccessToken(r) {
    r.subrequest("/_oauth2_send_request", function (reply) {
        try {
            if (reply.status === 200) {
                r.return(204);
            } else {
                r.return(401);
            }
        } catch (error) {
            r.return(500);
        }
    });
}

export default {
    introspectAccessToken
};