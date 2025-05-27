@extends('layouts.template.mahasiswa')
@section('title', 'List Topik Tugas Akhir')
<style>
    .text-primary-donk {
        color: #004085 !important;
        /* Biru tua/navy */
    }
</style>

        /* Hover button take topic */
        .btn-take:hover {
            background-color: #267acc !important;
            box-shadow: 0 6px 16px #267acccc;
        }
    </style>
@endpush

<table class="table table-bordered mt-4">
    @csrf
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Topic</th>
            <th>Description</th>
            <th>Quota</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Mobile Application Development</td>
            <td>Research on developing mobile applications for Android or iOS platforms.</td>
            <td>5</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Geographic Information System</td>
            <td>Research on implementing GIS for mapping specific areas.</td>
            <td>3</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Machine Learning</td>
            <td>Research on applying machine learning algorithms for data prediction.</td>
            <td>4</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>4</td>
            <td>Internet of Things (IoT)</td>
            <td>Research on developing IoT-based systems for automation.</td>
            <td>6</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>5</td>
            <td>Network Security</td>
            <td>Research on techniques to enhance computer network security.</td>
            <td>2</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>6</td>
            <td>Cloud Computing</td>
            <td>Research on cloud infrastructure and services for scalable applications.</td>
            <td>4</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>7</td>
            <td>Blockchain Technology</td>
            <td>Research on blockchain applications beyond cryptocurrency.</td>
            <td>3</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>8</td>
            <td>Artificial Intelligence</td>
            <td>Research on AI techniques for natural language processing.</td>
            <td>5</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>9</td>
            <td>Big Data Analytics</td>
            <td>Research on analyzing large datasets for business insights.</td>
            <td>4</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>10</td>
            <td>Cybersecurity</td>
            <td>Research on advanced methods to prevent cyber threats.</td>
            <td>3</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>11</td>
            <td>Data Mining</td>
            <td>Research on extracting meaningful patterns from large datasets.</td>
            <td>5</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>12</td>
            <td>Robotics</td>
            <td>Research on designing and programming autonomous robots.</td>
            <td>2</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>13</td>
            <td>Virtual Reality</td>
            <td>Research on developing immersive VR applications.</td>
            <td>4</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>14</td>
            <td>Augmented Reality</td>
            <td>Research on AR applications for education and training.</td>
            <td>3</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>15</td>
            <td>Quantum Computing</td>
            <td>Research on quantum algorithms for complex problem-solving.</td>
            <td>2</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>16</td>
            <td>Game Development</td>
            <td>Research on creating engaging and interactive video games.</td>
            <td>6</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>17</td>
            <td>Human-Computer Interaction</td>
            <td>Research on improving user interfaces for better usability.</td>
            <td>4</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>18</td>
            <td>Renewable Energy Systems</td>
            <td>Research on optimizing renewable energy technologies.</td>
            <td>3</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>19</td>
            <td>Digital Forensics</td>
            <td>Research on techniques for investigating digital crimes.</td>
            <td>2</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
        <tr>
            <td>20</td>
            <td>Autonomous Vehicles</td>
            <td>Research on self-driving car technologies and safety measures.</td>
            <td>5</td>
            <td><button class="btn btn-primary btn-sm">Take Topic</button></td>
        </tr>
    </tbody>
</table>
@endsection
