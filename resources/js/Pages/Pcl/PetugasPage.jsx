import React from 'react'
import MainLayout from '../MainLayout'
import { Box, Card, CardContent, CardHeader, Grid, TextField, Typography, useTheme } from '@mui/material'

function PetugasPage() {

    const theme = useTheme();

    return (
        <MainLayout>
            <Card>
                <CardHeader
                title={'Pengajuan Ganti Sampel'} style={{backgroundColor: theme.palette.primary}}>
                </CardHeader>
                <CardContent>
                    <Grid container>
                        <Grid item xs={12} sm={12} md={8} lg={6}>
                            <Typography variant='h6' mb={1}>Cari Sampel</Typography>
                            <Typography variant='caption'>Ketik kode atau nama sampel yang tidak ditemukan. Sampel yang tidak ditemukan akan diajukan penggantian sampel kepada Subject Matter Seksi Distribusi BPS Kota Surabaya</Typography>
                            <Box mt={2}>
                                <TextField fullWidth label={'Kode atau Nama Perusahaan'} />
                            </Box>
                        </Grid>
                    </Grid>
                </CardContent>
            </Card>
        </MainLayout>
    )
}

export default PetugasPage